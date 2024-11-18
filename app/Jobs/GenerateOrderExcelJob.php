<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\ProjectPrice;
use App\Models\Design;
use App\Models\Project;
use App\Models\InvoiceType;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Foundation;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FoundationOrderFileJob;

class GenerateOrderExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180;

    protected $projectId;
    protected $designId;
    protected $rowCounter;
    protected $smetaTotalLabour;
    protected $smetaTotalMaterial;
    protected $smetaTotalShipping;
    protected $labourIncluded;

    protected const ROW_ADDITIONAL_LINE_START = 9;
    protected const ROW_SECTION_START = 12;
    protected const ROW_NORMAL_ITEM = 13;
    protected const ROW_SECTION_TOTAL = 14;
    protected const ROW_INVOICE_TOTAL = 16;
    protected const ROW_SECTION_DELIVERY_TOTAL = 18;
    protected const ROW_FINAL_BOX_FIRST = 20;
    protected const ROW_FINAL_BOX_LAST = 28;
    protected const ROW_ADDITIONAL_LEFT = 36;
    protected const ROW_ADDITIONAL_RIGHT = 34;
    protected const ROW_ADDITIONAL_BOTH = 31;
    protected const ROW_EMPTY = 65;
    
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
        $this->rowCounter = 12;
        $this->smetaTotalLabour = 0;
        $this->smetaTotalMaterial = 0;
        $this->labourIncluded = false;
    }

    public function handle()
    {
        $startTime = microtime(true);
        
        $project = Project::findOrFail($this->projectId);

        $this->labourIncluded = ($project->price_type === 'smeta_project_labour' || str_contains($project->price_type, 'foundation'));
        //dd($this->labourIncluded, $project->price_type);

        $spreadsheet = IOFactory::createReader('Xlsx')->load(storage_path('templates/empty.xlsx'));

        $worksheet = $spreadsheet->createSheet();
        $worksheet->setTitle('Смета');
        $templateSheet = $spreadsheet->getSheet(0);
        
        // Copy basic sheet properties
        $sheetPropertiesTime = microtime(true);
        $this->copySheetProperties($templateSheet, $worksheet);
        Log::info('Copy sheet properties time: ' . (microtime(true) - $sheetPropertiesTime) . ' seconds');

        // Copy first 11 rows as is
        $firstRowsTime = microtime(true);
        for ($i = 1; $i <= 11; $i++) {
            $this->copyMergedCells($templateSheet, $worksheet, $i, $i);
            $this->copyRowFormatAndStyle($templateSheet, $i, $worksheet, $i);
            $this->copyRowContent($templateSheet, $worksheet, $i, $i);
        }
        Log::info('Copy first 11 rows time: ' . (microtime(true) - $firstRowsTime) . ' seconds');

        $currentRow = 12;

        if ($project->foundation_id) {
            // Foundation order
            $foundationTime = microtime(true);
            
            $foundation = Foundation::findOrFail($project->foundation_id);
            dispatch(new FoundationOrderFileJob($project));
            sleep(4);
            if (!$foundation) {
                Log::error("Foundation not found for project ID: {$this->projectId}");
                return;
            }

            if (!$project->foundation_params) {
                Log::error("Foundation params not found for project ID: {$this->projectId}");
                return;
            }

            $sheetData = $project->foundation_params['sheet_structure'];
            $invoiceTitle = $project->foundation_params['sheet_structure']['title'];
            $currentRow = $this->fillWorksheet($spreadsheet, $worksheet, $sheetData, $currentRow, true, $invoiceTitle);
            
            Log::info('Foundation order processing time: ' . (microtime(true) - $foundationTime) . ' seconds');
            
        } else if ($project->design_id) {
            // Design-based order
            $designTime = microtime(true);
            
            $design = Design::findOrFail($project->design_id);
            $invoiceTypes = $project->selected_configuration;
            $invoiceTypeDescriptions = $project->configuration_descriptions;
            $invoiceTypeOrder = ['foundation', 'dd', 'roof'];

            $isFirstInvoice = true;
            $counter = 0;
            foreach ($invoiceTypeOrder as $invoiceTypeRef) {
                if (isset($invoiceTypes[$invoiceTypeRef])) {
                    $order = ++$counter;
                    $invoiceType = InvoiceType::where('ref', $invoiceTypes[$invoiceTypeRef])->first();
                    
                    if ($invoiceType) {
                        $invoiceTitle = str_replace('{order}', $order, $invoiceType->custom_order_title);
                        $invoiceTypeDescription = $invoiceTypeDescriptions[$invoiceTypeRef];
                        
                        $projectPrice = ProjectPrice::where('invoice_type_id', $invoiceType->id)
                                                    ->where('design_id', $design->id)
                                                    ->first();
                        if ($projectPrice) {
                            $data = json_decode($projectPrice->parameters, true);
                            $sheetData = $data['sheet_structure'];
                            
                            $currentRow = $this->fillWorksheet($spreadsheet, $worksheet, $sheetData, $currentRow, $isFirstInvoice, $invoiceTitle);
                            $isFirstInvoice = false;
                        } else {
                            Log::error("ProjectPrice not found for invoice type ID: {$invoiceType->id} and design ID: {$design->id}");
                        }
                    }
                }
            }
            
            Log::info('Design-based order processing time: ' . (microtime(true) - $designTime) . ' seconds');
        } else {
            Log::error("Project ID: {$this->projectId} has neither foundation_id nor design_id");
            return;
        }

        // Remove the grand total after all invoices
        $this->insertEmptyRow($templateSheet, $worksheet, $currentRow);

        //Setting up Box / Final row items
        $finalRowsTime = microtime(true);
        if (!$this->labourIncluded) {
            $this->smetaTotalLabour = 0;
        }
        $lineRow = [
            self::ROW_FINAL_BOX_FIRST => $this->smetaTotalLabour,
            (self::ROW_FINAL_BOX_FIRST)+1 => round($this->smetaTotalLabour*0.03, 2),
            (self::ROW_FINAL_BOX_FIRST)+2 => round($this->smetaTotalLabour*0.16, 2),
            (self::ROW_FINAL_BOX_FIRST)+3 => round(($this->smetaTotalLabour + $this->smetaTotalLabour*0.03 + $this->smetaTotalLabour*0.16), 2),
            (self::ROW_FINAL_BOX_FIRST)+4 => round($this->smetaTotalMaterial, 2),
            (self::ROW_FINAL_BOX_FIRST)+5 => round($this->smetaTotalMaterial*0.035, 2),
            (self::ROW_FINAL_BOX_FIRST)+6 => round($this->smetaTotalShipping, 2),
            (self::ROW_FINAL_BOX_FIRST)+7 => round(($this->smetaTotalMaterial + $this->smetaTotalMaterial*0.035 + $this->smetaTotalShipping), 2),
        ];
        $rowsToSkipWithNoLabour = [
            /*
            self::ROW_FINAL_BOX_FIRST, 
            self::ROW_FINAL_BOX_FIRST+1, 
            self::ROW_FINAL_BOX_FIRST+2, 
            self::ROW_FINAL_BOX_FIRST+3,
            self::ROW_FINAL_BOX_FIRST+4,
            self::ROW_FINAL_BOX_FIRST+5,
            self::ROW_FINAL_BOX_FIRST+6,
            self::ROW_FINAL_BOX_FIRST+7,
            */
        ];
        $lineRow[self::ROW_FINAL_BOX_LAST] = round($lineRow[self::ROW_FINAL_BOX_FIRST+3] + $lineRow[self::ROW_FINAL_BOX_FIRST+7], 2);

        $this->processFinalRows($templateSheet, $worksheet, $lineRow, $currentRow, $rowsToSkipWithNoLabour);
        Log::info('Final rows processing time: ' . (microtime(true) - $finalRowsTime) . ' seconds');

        // Remove the template sheet and save the spreadsheet
        $saveTime = microtime(true);
        $spreadsheet->removeSheetByIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $publicPath = public_path('orders');
        $filename = $this->projectId . "_" . $this->designId . "_" . time() . ".xlsx";
        $outputPath = $publicPath . '/' . $filename;
        $writer->save($outputPath);
        $publicUrl = url('orders/' . $filename);
        $project->update(['filepath' => $publicUrl]);
        gc_collect_cycles();
        Log::info('Save spreadsheet time: ' . (microtime(true) - $saveTime) . ' seconds');
        
        Log::info('Total execution time: ' . (microtime(true) - $startTime) . ' seconds');
    }

    protected function processFinalRows($templateSheet, $worksheet, $lineRow, &$currentRow, $rowsToSkipWithNoLabour)
    {
        $totalSum = 0;

        foreach ($lineRow as $templateRowNumber => $value) {
            if (in_array($templateRowNumber, $rowsToSkipWithNoLabour)) {
                continue;
            }
            $this->copyMergedCells($templateSheet, $worksheet, $templateRowNumber, $currentRow);
            $this->copyRowFormatAndStyle($templateSheet, $templateRowNumber, $worksheet, $currentRow);
            $this->copyRowContent($templateSheet, $worksheet, $templateRowNumber, $currentRow);
            $worksheet->setCellValue("H{$currentRow}", $value);
            $worksheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
            $worksheet->getStyle("A{$currentRow}:M{$currentRow}")->getFont()->setBold(true);
            $worksheet->getStyle("A{$currentRow}:M{$currentRow}")->getFont()->setSize(12.5);
            $worksheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $worksheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $worksheet->getStyle("E{$currentRow}:H{$currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
            $worksheet->getStyle("E{$currentRow}:H{$currentRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EBEBEB');

            $worksheet->getStyle("E{$currentRow}:H{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            $totalSum += $value;
            $currentRow++;
        }
    }

    public function insertEmptyRow($templateSheet, $worksheet, &$currentRow)
    {
        $templateRowNumber = self::ROW_EMPTY; // The row number we're copying from the template

        // Copy merged cells
        $this->copyMergedCells($templateSheet, $worksheet, $templateRowNumber, $currentRow);

        // Copy row format and style
        $this->copyRowFormatAndStyle($templateSheet, $templateRowNumber, $worksheet, $currentRow);

        // Copy row content (which should be empty for row 30)
        $this->copyRowContent($templateSheet, $worksheet, $templateRowNumber, $currentRow);

        // Ensure the row is actually empty
        foreach ($worksheet->getRowIterator($currentRow, $currentRow) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cell->setValue(null);
            }
        }

        // Increment the current row
        $currentRow++;
    }

    public function insertLastSectionTotalRow($templateSheet, $worksheet, &$currentRow)
    {
        $templateRowNumber = 18; // The row number we're copying from the template

        // Copy merged cells
        $this->copyMergedCells($templateSheet, $worksheet, $templateRowNumber, $currentRow);

        // Copy row format and style
        $this->copyRowFormatAndStyle($templateSheet, $templateRowNumber, $worksheet, $currentRow);

        // Copy row content (which should be empty for row 30)
        $this->copyRowContent($templateSheet, $worksheet, $templateRowNumber, $currentRow);

        $worksheet->getStyle("G{$currentRow}")->getFont()->setName("Arial");
        $worksheet->getStyle("M{$currentRow}")->getFont()->setName("Arial");

        // Ensure the row is actually empty
        foreach ($worksheet->getRowIterator($currentRow, $currentRow) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cell->setValue(null);
            }
        }

        // Increment the current row
        $currentRow++;
    }

    protected function fillWorksheet($spreadsheet, $worksheet, $sheetData, $startRow, $isFirstInvoice, $invoiceTitle)
    {
        $fillWorksheetTime = microtime(true);
        
        $currentRow = $startRow+1;
        $additionalLineRow = 8;
        //Log::info("Starting fillWorksheet at row: $currentRow");
        
        $templateSheet = $spreadsheet->getSheet(0);

        if ($isFirstInvoice) {
            $currentRow--;
            $worksheet->setCellValue("A7", $invoiceTitle);
            $worksheet->getStyle("A7")->getFont()->setBold(true);
            $worksheet->getStyle("A7")->getFont()->setSize(13.5);
            
            if (isset($sheetData['additionalLinesExist']) && $sheetData['additionalLinesExist']) {
                $additionalLineRow = self::ROW_ADDITIONAL_LINE_START;
                foreach ($sheetData['additionalLines']['rows'] as $lineToCopy) {
                    $worksheet->insertNewRowBefore($additionalLineRow, 1);
                    $this->copyRowFormatContentAndMerges($templateSheet, $worksheet, $lineToCopy, $additionalLineRow);

                    // Replace dynamic swaps with actual values
                    foreach ($sheetData['additionalLines']['dynamicSwapSearchCols'] as $searchCol) {
                        $cellValue = $worksheet->getCell($searchCol . $additionalLineRow)->getValue();
                        foreach ($sheetData['additionalLines']['dynamicCellSwaps'] as $key => $value) {
                            if (strpos($cellValue, $key) !== false) {
                                $cellValue = str_replace($key, $value, $cellValue);
                                $worksheet->setCellValue($searchCol . $additionalLineRow, $cellValue);
                            }
                        }
                    }

                    // Apply formatting to the row
                    $formatting = $sheetData['additionalLines']['formatting'];

                    // Apply borders
                    if (isset($formatting['borders'])) {
                        foreach ($formatting['borders'] as $col => $borderData) {
                            $borderStyle = $borderData['borderStyle'];
                            $worksheet->getStyle($col . $additionalLineRow)->getBorders()->getAllBorders()->setBorderStyle($borderStyle);
                        }
                    }

                    // Apply alignment
                    if (isset($formatting['alignment'])) {
                        foreach ($formatting['alignment'] as $col => $alignment) {
                            $worksheet->getStyle($col . $additionalLineRow)->getAlignment()->setHorizontal($alignment);
                        }
                    }

                    // Apply fill
                    if (isset($formatting['fill'])) {
                        foreach ($formatting['fill'] as $col => $fillColor) {
                            $worksheet->getStyle($col . $additionalLineRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($fillColor);
                        }
                    }

                    // Apply font
                    if (isset($formatting['font'])) {
                        foreach ($formatting['font'] as $col => $fontData) {
                            $fontStyle = $worksheet->getStyle($col . $additionalLineRow)->getFont();
                            if (isset($fontData['name'])) {
                                $fontStyle->setName($fontData['name']);
                            }
                            if (isset($fontData['size'])) {
                                $fontStyle->setSize($fontData['size']);
                            }
                            if (isset($fontData['bold']) && $fontData['bold']) {
                                $fontStyle->setBold(true);
                            }
                        }
                    }

                    $additionalLineRow++;
                    $currentRow++;
                }
            }
        } else {
            $currentRow--;
            $this->insertEmptyRow($templateSheet, $worksheet, $currentRow);
            $this->copyMergedCells($templateSheet, $worksheet, 7, $currentRow);
            $this->copyRowFormatAndStyle($templateSheet, 7, $worksheet, $currentRow);
            
            $this->copyRowContent($templateSheet, $worksheet, 7, $currentRow);
            $worksheet->setCellValue("A{$currentRow}", $invoiceTitle);
            $worksheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
            $worksheet->getStyle("A{$currentRow}")->getFont()->setSize(13.5);
            //Log::info("Inserted and set invoice title at row: $currentRow");
            $currentRow++;
        }
        
        $invoiceTotalLabour = 0;
        $invoiceTotalMaterial = 0;

        $sections = array_values($sheetData['sections']);
        $lastSectionIndex = count($sections) - 1;

        foreach ($sections as $sectionIndex => $section) {
            // Calculate labour and material totals for the section
            $lastSection = $sectionIndex == $lastSectionIndex;
            $sectionTotalsTime = microtime(true);
            $sectionTotals = $this->calculateSectionTotals($section);
            Log::info('Calculate section totals time: ' . (microtime(true) - $sectionTotalsTime) . ' seconds');
            
            $labourTotal = $sectionTotals['labourTotal'];
            $materialTotal = $sectionTotals['materialTotal'];

            // Skip this section if both labour and material totals are zero
            if ($labourTotal == 0 && $materialTotal == 0) {
                //Log::info("Skipping section $sectionIndex as both totals are zero");
                continue;
            }
            //Log::info("Starting section $sectionIndex at row: $currentRow");
            
            // Copy row 12 with merged cells, format, style, and change "СЕКЦИЯ" to section name
            $copyRowTime = microtime(true);
            $this->copyMergedCells($templateSheet, $worksheet, self::ROW_SECTION_START, $currentRow);
            $this->copyRowFormatAndStyle($templateSheet, self::ROW_SECTION_START, $worksheet, $currentRow);
            $this->copyRowContent($templateSheet, $worksheet, self::ROW_SECTION_START, $currentRow);
            Log::info('Copy row format, style, and content time: ' . (microtime(true) - $copyRowTime) . ' seconds');
            
            $worksheet->setCellValue("A{$currentRow}", $section['value']);
            $this->copyCellStyle($templateSheet->getCell("A12"), $worksheet->getCell("A{$currentRow}"));
            $worksheet->getStyle("A{$currentRow}")->getFont()->setSize(12);
            $currentRow++;
            //Log::info("Section header set, new row: $currentRow");

            $maxRows = max(count($section['labourItems']), count($section['materialItems']));

            $processItemsTime = microtime(true);
            for ($i = 0; $i < $maxRows; $i++) {
                // Modify this condition to always process rows in the last section
                $subheadingNoPrice = false;
                if ($lastSection || (isset($section['labourItems'][$i]) && isset($section['materialItems'][$i]))) {
                    $labour = $section['labourItems'][$i] ?? ['labourTotal' => 0];
                    
                    //TO DELETE
                    



                    $material = $section['materialItems'][$i] ?? ['materialTotal' => 0];
                    $labourSubHeading = $section['labourItems'][$i]['labourSubHeading'] ?? false;
                    $materialSubHeading = $section['materialItems'][$i]['materialSubHeading'] ?? false;
                    if (is_null($section['labourItems'][$i]['labourTotal']) && $section['materialItems'][$i]['materialTotal']==0 && !$lastSection && !$labourSubHeading && !$materialSubHeading) {
                        continue;
                    }
                    if ($section['labourItems'][$i]['labourTotal'] == 0 && is_null($section['materialItems'][$i]['materialTotal']) && !$lastSection && !$labourSubHeading && !$materialSubHeading) {
                        continue;
                    }
                    if ($section['labourItems'][$i]['labourTotal'] == 0 && $section['materialItems'][$i]['materialTotal'] == 0 && !$lastSection && !$labourSubHeading && !$materialSubHeading) {
                        continue;
                    }
                    if (isset($material['materialSubheadingParams']['entireRow']) && $material['materialSubheadingParams']['entireRow']) {
                        $subheadingNoPrice = true;
                    }
                } 
                
                if (!$labour['labourAdditional'] && !$material['materialAdditional']) {
                    $this->copyMergedCells($templateSheet, $worksheet, self::ROW_NORMAL_ITEM, $currentRow);
                    $this->copyRowFormatAndStyle($templateSheet, self::ROW_NORMAL_ITEM, $worksheet, $currentRow);
                    $this->copyRowContent($templateSheet, $worksheet, self::ROW_NORMAL_ITEM, $currentRow);
                } elseif ($section['labourItems'][$i]['labourTitle'] != null && $section['labourItems'][$i]['labourAdditional'] && !$section['materialItems'][$i]['materialAdditional']) {
                    $this->copyMergedCells($templateSheet, $worksheet, self::ROW_ADDITIONAL_LEFT, $currentRow);
                    $this->copyRowFormatAndStyle($templateSheet, self::ROW_ADDITIONAL_LEFT, $worksheet, $currentRow);
                    $this->copyRowContent($templateSheet, $worksheet, self::ROW_ADDITIONAL_LEFT, $currentRow);
                } elseif ($section['materialItems'][$i]['materialTitle'] != null && !$section['labourItems'][$i]['labourAdditional'] && $section['materialItems'][$i]['materialAdditional']) {
                    $this->copyMergedCells($templateSheet, $worksheet, self::ROW_ADDITIONAL_RIGHT, $currentRow);
                    $this->copyRowFormatAndStyle($templateSheet, self::ROW_ADDITIONAL_RIGHT, $worksheet, $currentRow);
                    $this->copyRowContent($templateSheet, $worksheet, self::ROW_ADDITIONAL_RIGHT, $currentRow);
                } elseif ($section['labourItems'][$i]['labourTitle'] != null && $section['materialItems'][$i]['materialTitle'] != null && $section['labourItems'][$i]['labourAdditional'] && $section['materialItems'][$i]['materialAdditional']) {
                    $this->copyMergedCells($templateSheet, $worksheet, self::ROW_ADDITIONAL_BOTH, $currentRow);
                    $this->copyRowFormatAndStyle($templateSheet, self::ROW_ADDITIONAL_BOTH, $worksheet, $currentRow);
                    $this->copyRowContent($templateSheet, $worksheet, self::ROW_ADDITIONAL_BOTH, $currentRow);
                } else continue;

                if (isset($section['labourItems'][$i]) && $section['labourItems'][$i]['labourTitle'] != null) {

                    
                    $labour = $section['labourItems'][$i];
                    
                    if (Str::length($labour['labourTitle']) > 60) {
                        $worksheet->getRowDimension($currentRow)->setRowHeight(30);
                    }
                    $worksheet->setCellValue("A{$currentRow}", $labour['labourNumber']);
                    $worksheet->setCellValue("B{$currentRow}", $labour['labourTitle']);
                    $worksheet->getCell("B{$currentRow}")->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    if ($labour['labourAdditional']) {
                        $worksheet->setCellValue("C{$currentRow}", $labour['labourAdditionalTitle']);
                    }
                    $worksheet->setCellValue("D{$currentRow}", $labour['labourUnit']);
                    $worksheet->setCellValue("E{$currentRow}", $labour['labourQuantity']);
                    $worksheet->getCell("E{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode($labour['labourFormat']);
                    if ($this->labourIncluded && !$labourSubHeading) {
                        if (is_numeric($labour['labourPrice']) && !is_null($labour['labourPrice']) && $labour['labourPrice'] != 0) {
                            $worksheet->setCellValue("F{$currentRow}", $labour['labourPrice']);
                            $worksheet->getCell("F{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                        }
                        $worksheet->setCellValue("G{$currentRow}", $labour['labourTotal']);
                        $worksheet->getCell("G{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                    } else if (!$this->labourIncluded) {
                        $worksheet->setCellValue("F{$currentRow}", 0);
                        $worksheet->getCell("F{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                        $worksheet->setCellValue("G{$currentRow}", 0);
                        $worksheet->getCell("G{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                    }
                }

                if (isset($section['materialItems'][$i]) && $section['materialItems'][$i]['materialTitle'] != null) {
                    $material = $section['materialItems'][$i];
                    
                    if (Str::length($material['materialTitle']) > 56) {
                        $worksheet->getRowDimension($currentRow)->setRowHeight(30);
                    }
                    
                    if ($lastSection) {
                        $material['materialTotal'] = 0;
                        $material['materialPrice'] = 0;
                    }
                    //Log::info("Setting material item: {$material['materialTitle']} at row: $currentRow");
                    $worksheet->setCellValue("H{$currentRow}", $material['materialTitle']);
                    $worksheet->getCell("H{$currentRow}")->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    if (isset($material['materialSubHeading']) && $material['materialSubHeading']) {
                        $worksheet->getStyle("H{$currentRow}")->getFont()->setBold(true);
                    }
                    if (!$subheadingNoPrice) {
                    
                        $worksheet->setCellValue("K{$currentRow}", $material['materialQuantity']);
                        if ($material['materialAdditional']) {
                            $worksheet->setCellValue("I{$currentRow}", $material['materialAdditionalTitle']);
                        }
                        $worksheet->setCellValue("J{$currentRow}", $material['materialUnit']);
                        
                        // Always set these values for the last section, even if they're zero
                        
                        if ($lastSection || (is_numeric($material['materialPrice']) && !is_null($material['materialPrice']) && $material['materialPrice'] != 0)) {
                            $worksheet->setCellValue("K{$currentRow}", $material['materialQuantity']);
                            if (isset($material['materialFormat'])) {
                                $worksheet->getCell("K{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode($material['materialFormat']);
                            } else {
                                $worksheet->getCell("K{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("0");
                            }
                            $worksheet->setCellValue("L{$currentRow}", $material['materialPrice']);
                            $worksheet->getCell("L{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                            if ($material['materialUnit'] == 'дл./шт.') {
                                $worksheet->getCell("L{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("0");
                            }
                            $worksheet->setCellValue("M{$currentRow}", $material['materialTotal']);
                            $worksheet->getCell("M{$currentRow}")->getStyle()->getNumberFormat()->setFormatCode("#,##0.00");
                        }
                    }
                    
                }

                $currentRow++;
                //Log::info("Finished processing row, new row: $currentRow");
            }
            Log::info('Process labour and material items time: ' . (microtime(true) - $processItemsTime) . ' seconds');
            
            //Log::info("Calculated section totals - Labour: $labourTotal, Material: $materialTotal");

            // Insert last section total row
            if ($sectionIndex !== $lastSectionIndex) {
                $this->copyAndSetTotalRow($templateSheet, $worksheet, self::ROW_SECTION_TOTAL, $currentRow, $labourTotal, $materialTotal);
            } else $this->copyAndSetTotalRow($templateSheet, $worksheet, self::ROW_SECTION_DELIVERY_TOTAL, $currentRow, $labourTotal, $materialTotal);
            
            
            //Log::info("Set section total row at: $currentRow");
            
            // empty row
            $currentRow++;
            $this->insertEmptyRow($templateSheet, $worksheet, $currentRow);
            //Log::info("Moving to next section, new row: $currentRow");
            
            $invoiceTotalLabour += $labourTotal;
            // Only add material total if it's not the last section
            if ($sectionIndex !== $lastSectionIndex) {
                $invoiceTotalMaterial += $materialTotal;
            } else $this->smetaTotalShipping += $materialTotal;
        }

        // Add invoice totals only if there are non-zero sections
        if ($invoiceTotalLabour > 0 || $invoiceTotalMaterial > 0) {
            $this->copyAndSetTotalRow($templateSheet, $worksheet, self::ROW_INVOICE_TOTAL, $currentRow, $invoiceTotalLabour, $invoiceTotalMaterial);
            $worksheet->getStyle("A{$currentRow}:M{$currentRow}")->getFont()->setSize(12.4);
            //Log::info("Set invoice total row at: $currentRow");

            $this->smetaTotalLabour += $invoiceTotalLabour;
            $this->smetaTotalMaterial += $invoiceTotalMaterial;
        } else {
            //Log::info("Skipping invoice totals as all sections were zero");
        }

        $this->rowCounter = $currentRow;
        //Log::info("Finished all sections, final row: $currentRow");
        
        // Return the next row number for the next invoice
        // empty row
        $currentRow++;
        $this->insertEmptyRow($templateSheet, $worksheet, $currentRow);
        return $currentRow;
    }

    private function setCellValueWithFormat($worksheet, $cell, $value, $decimals, $forceDecimals = false)
    {
        $roundedValue = round($value, $decimals);
        $worksheet->setCellValue($cell, $roundedValue);

        if ($forceDecimals) {
            $format = '#,##0.' . str_repeat('0', $decimals);
        } else {
            if ($value < 10000) {
                $format = ($roundedValue == floor($roundedValue)) ? '#,##0' : '#,##0.' . str_repeat('#', $decimals);
            } else {
                $format = '#,##0';
            }
        }

        $worksheet->getCell($cell)->getStyle()->getNumberFormat()->setFormatCode($format);
    }

    protected function calculateSectionTotals($section)
    {
        $labourTotal = 0;
        $materialTotal = 0;

        foreach ($section['labourItems'] as $labour) {
            if (isset($labour['labourTotal']) && is_numeric($labour['labourTotal'])) {
                $labourTotal += $labour['labourTotal'];
            }
        }

        foreach ($section['materialItems'] as $material) {
            if (isset($material['materialTotal']) && is_numeric($material['materialTotal'])) {
                $materialTotal += $material['materialTotal'];
            }
        }

        return [
            'labourTotal' => $labourTotal,
            'materialTotal' => $materialTotal
        ];
    }

    protected function copyAndSetTotalRow($templateSheet, $worksheet, $templateRow, $currentRow, $columnG, $columnM)
    {
        $this->copyMergedCells($templateSheet, $worksheet, $templateRow, $currentRow);
        $this->copyRowFormatAndStyle($templateSheet, $templateRow, $worksheet, $currentRow);
        $this->copyRowContent($templateSheet, $worksheet, $templateRow, $currentRow);
        
        $worksheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $worksheet->getStyle("M{$currentRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $worksheet->setCellValue("G" . $currentRow, $columnG);
        $worksheet->setCellValue("M" . $currentRow, $columnM);
        
        if ($templateRow != self::ROW_INVOICE_TOTAL) {
            $colLetterLabour = "E";
            $colLetterMaterial = "K";
        } else {
            $colLetterLabour = "D";
            $colLetterMaterial = "J";
            $worksheet->getStyle("{$colLetterLabour}{$currentRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EBEBEB');
            $worksheet->getStyle("{$colLetterMaterial}{$currentRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EBEBEB');
            $worksheet->getStyle("G{$currentRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EBEBEB');
            $worksheet->getStyle("M{$currentRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('EBEBEB');
        }
        $worksheet->getStyle("{$colLetterLabour}{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $worksheet->getStyle("{$colLetterMaterial}{$currentRow}:M{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $worksheet->getStyle("{$colLetterLabour}{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("{$colLetterMaterial}{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("M{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("G{$currentRow}")->getFont()->setBold(true);
        $worksheet->getStyle("M{$currentRow}")->getFont()->setBold(true);
        $worksheet->getStyle("{$colLetterLabour}{$currentRow}")->getFont()->setName("Arial");
        $worksheet->getStyle("{$colLetterMaterial}{$currentRow}")->getFont()->setName("Arial");
        $worksheet->getStyle("{$colLetterLabour}{$currentRow}")->getFont()->setBold(true);
        $worksheet->getStyle("{$colLetterMaterial}{$currentRow}")->getFont()->setBold(true);
        $worksheet->getStyle("G{$currentRow}")->getFont()->setName("Arial");
        $worksheet->getStyle("M{$currentRow}")->getFont()->setName("Arial");
        if (!$this->labourIncluded) {
            $worksheet->setCellValue("G" . $currentRow, 0);
        }
    }

    protected function copySheetProperties($fromSheet, $toSheet)
    {
        // Copy column widths
        foreach ($fromSheet->getColumnDimensions() as $columnId => $columnDimension) {
            $toSheet->getColumnDimension($columnId)->setWidth($columnDimension->getWidth());
        }

        // Copy row heights
        foreach ($fromSheet->getRowDimensions() as $rowId => $rowDimension) {
            $toSheet->getRowDimension($rowId)->setRowHeight($rowDimension->getRowHeight());
        }

        // Copy sheet properties
        $toSheet->getPageSetup()->setFitToPage($fromSheet->getPageSetup()->getFitToPage());
        $toSheet->getPageSetup()->setFitToWidth($fromSheet->getPageSetup()->getFitToWidth());
        $toSheet->getPageSetup()->setFitToHeight($fromSheet->getPageSetup()->getFitToHeight());
    }

    protected function copyRowFormatContentAndMerges($fromSheet, $toSheet, $fromRow, $toRow)
    {
        $this->copyMergedCells($fromSheet, $toSheet, $fromRow, $toRow);
        $this->copyRowFormatAndStyle($fromSheet, $fromRow, $toSheet, $toRow);
        $this->copyRowContent($fromSheet, $toSheet, $fromRow, $toRow);
    }

    protected function copyMergedCells($fromSheet, $toSheet, $fromRow, $toRow)
    {
        foreach ($fromSheet->getMergeCells() as $mergeCell) {
            $mergeCellRange = Coordinate::splitRange($mergeCell);
            $mergeCellStart = Coordinate::coordinateFromString($mergeCellRange[0][0]);
            $mergeCellEnd = Coordinate::coordinateFromString($mergeCellRange[0][1]);
            
            if ($mergeCellStart[1] == $fromRow) {
                $newMergeCell = $mergeCellStart[0] . $toRow . ':' . $mergeCellEnd[0] . $toRow;
                $toSheet->mergeCells($newMergeCell);
            }
        }
    }

    protected function copyRowFormatAndStyle($fromSheet, $fromRow, $toSheet, $toRow)
    {
        $range = "A{$fromRow}:M{$fromRow}";
        $toRange = "A{$toRow}:M{$toRow}";
        
        // Copy style directly
        $toSheet->getStyle($toRange)->applyFromArray(
            $fromSheet->getStyle($range)->exportArray()
        );

        // Explicitly copy number format and individual cell styles
        foreach (range('A', 'M') as $column) {
            $fromCell = $fromSheet->getCell("{$column}{$fromRow}");
            $toCell = $toSheet->getCell("{$column}{$toRow}");
            $toCell->getStyle()->applyFromArray(
                $fromCell->getStyle()->exportArray()
            );
            $toCell->getStyle()->getNumberFormat()->setFormatCode(
                $fromCell->getStyle()->getNumberFormat()->getFormatCode()
            );
        }
    }

    protected function copyRowContent($fromSheet, $toSheet, $fromRow, $toRow)
    {
        foreach ($fromSheet->getRowIterator($fromRow, $fromRow) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $toSheet->setCellValue(
                    $cell->getColumn() . $toRow,
                    $cell->getValue()
                );

                // Ensure the style is copied for each cell
                $this->copyCellStyle($cell, $toSheet->getCell($cell->getColumn() . $toRow));
            }
        }
    }

    protected function copyCellStyle($fromCell, $toCell)
    {
        $fromStyle = $fromCell->getStyle()->exportArray();
        $toCell->getStyle()->applyFromArray($fromStyle);
    }
}