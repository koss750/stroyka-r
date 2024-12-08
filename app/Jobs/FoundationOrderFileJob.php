<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\Foundation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FoundationOrderFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;
    protected $spreadsheet;
    protected $cellMappings;
    protected $foundation;
    protected $foundationParams;
    protected const ROW_PAIR_A = [
        'rows' => [56, 57], 
        'formatting' => [
            'borders' => [
                'M' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'J' => Alignment::HORIZONTAL_RIGHT,
                'M' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'M' => 'EBEBEB',
            ],
            'font' => [
                'J' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
                'M' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
            ],
        ],
        'dynamicCellSwaps' => [
            '{J5}' => 'J5',
            '{K5}' => 'K5',
            '{L6}' => 'L6'
        ],
        'dynamicSwapSearchCols' => ["M"]
    ];
    protected const ROW_PAIR_B = [
        'rows' => [59, 60, 61], 
        'formatting' => [
            'borders' => [
                'M' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'J' => Alignment::HORIZONTAL_RIGHT,
                'M' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'M' => 'EBEBEB',
            ],
            'font' => [
                'M' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
                'J' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
            ],
        ],
        'dynamicCellSwaps' => [
            '{J5}' => 'J5',
            '{K5}' => 'K5',
            '{L6}' => 'L6',
            '{J6}' => 'J6'
        ],
        'dynamicSwapSearchCols' => ["M"]
    ];
    protected const ADDITIONAL_LINES_DATA = [
        'ROW_PAIR_A' => self::ROW_PAIR_A,
        'ROW_PAIR_B' => self::ROW_PAIR_B,
    ];
    
    /**
     * Create a new job instance.
     *
     * @param int $projectId
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project = $this->project;
        $this->foundation = Foundation::findOrFail($project->foundation_id);
        $foundation = $this->foundation;
        Log::info("Generating order file for Project ID: {$project->id}, Foundation ID: {$foundation->id}");
        $this->cellMappings = $project->selected_configuration;
        $this->loadSpreadsheet($foundation);
        $this->populateSpreadsheet();
        $this->processFoundation($foundation);
        $this->saveSpreadsheet($project);

            //Log::info('Order file generated successfully.');
          //  Log::info("Excel file link: " . Storage::url($project->filepath));
        //} catch (\Exception $e) {
        //    Log::error('Error generating foundation order file: ' . $e->getMessage());
        //}
    }

    protected function loadSpreadsheet($foundation)
    {
        $templatePath = storage_path('templates/foundation/' . $foundation->template_path);
        $this->spreadsheet = IOFactory::load($templatePath);
    }

    protected function populateSpreadsheet()
    {
        $sheet = $this->spreadsheet->getSheetByName("данные");
        foreach ($this->cellMappings as $cell => $value) {
            if (strpos($value, ",") !== false) {
                $value = str_replace(",", ".", $value);
                $value = (float)$value;
                $value = round($value, 2);
            }
            $sheet->setCellValue($cell, $value);
            Log::info($cell . " " . $value);
        }
        Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
    }

    protected function processFoundation()
    {
        $smetaSheet = $this->findSmetaSheet();
        if (!$smetaSheet) {
            throw new \Exception('Sheet with "Смета" not found');
        }

        $sheetStructure = $this->generateSheetStructure($smetaSheet);
        $sheetStructure =$this->fillCustomChanges($smetaSheet, $sheetStructure);
        // Get the current parameters
        $this->project->foundation_params = $sheetStructure;
        
        $this->project->save();
    }

    protected function findSmetaSheet()
    {
        foreach ($this->spreadsheet->getWorksheetIterator() as $worksheet) {
            if (strpos(strtolower($worksheet->getTitle()), 'смета') !== false) {
                return $worksheet;
            }
        }
        return null;
    }

    private function formatNumber($number, $decimalPlaces)
    {
        return number_format((float)$number, $decimalPlaces, '.', '');
    }

    protected function generateSheetStructure($sheet)
    {
        $params = $this->foundation->parameters; 
        $sheetStructure = $params['sheet_structure'];
        foreach ($sheetStructure['sections'] as $sectionKey => &$section) {
            $labourTotal = 0;
            $materialTotal = 0;
            $unsetArray = [];
            $sectionLabourTotal = 0;
            $sectionMaterialTotal = 0;
            foreach ($section['labourItems'] as &$labourItem) {
                $decimalPlaces = $labourItem['labourDecimalPlaces'];
                $quantity = $this->formatNumber($this->getCellValue($sheet, $labourItem['labourQuantityCell']), $decimalPlaces);
                $price = $this->formatNumber($this->getCellValue($sheet, $labourItem['labourPriceCell']), 2); // Always 2 decimals for prices

                if ($labourItem['labourAdditional']) {
                    $labourItem['labourAdditionalTitle'] = $this->getCellValue($sheet, $labourItem['labourAdditionalTitleCell']);
                }
                $labourItem['labourQuantity'] = $quantity;
                $labourItem['labourPrice'] = $price;
                $labourItem['labourTotal'] = $this->formatNumber($this->getCellValue($sheet, $labourItem['labourTotalCell']), 2); // Always 2 decimals for totals
                $labourTotal += $labourItem['labourTotal'];
                $sectionLabourTotal += $labourItem['labourTotal'];
            }

            foreach ($section['materialItems'] as &$materialItem) {
                $decimalPlaces = $materialItem['materialDecimalPlaces'];
                $quantity = $this->formatNumber($this->getCellValue($sheet, $materialItem['materialQuantityCell']), $decimalPlaces);
                if ($materialItem['materialAdditional']) {
                    $materialItem['materialAdditionalTitle'] = $this->getCellValue($sheet, $materialItem['materialAdditionalTitleCell']);
                }
                $price = $this->formatNumber($this->getCellValue($sheet, $materialItem['materialPriceCell']), 2); // Always 2 decimals for prices

                $materialItem['materialQuantity'] = $quantity;
                $materialItem['materialPrice'] = $price;
                $materialItem['materialTotal'] = $this->formatNumber($this->getCellValue($sheet, $materialItem['materialTotalCell']), 2); // Always 2 decimals for totals
                $materialTotal += $materialItem['materialTotal'];
                $sectionMaterialTotal += $materialItem['materialTotal'];
            }

            $section['sectionTotalLabour'] = $sectionLabourTotal;
            $section['sectionTotalMaterial'] = $sectionMaterialTotal;
            $section['sectionTotalValue'] = $sectionLabourTotal + $sectionMaterialTotal;
            if ($section['sectionTotalValue'] == 0) {
                $unsetArray[] = $sectionKey;
            }
        }

        $lastSectionKey = sizeof($sheetStructure['sections'])-1;
        if (isset($sheetStructure['sections'][$lastSectionKey])) {
            $params['sheet_structure']['shipping'] = $sheetStructure['sections'][$lastSectionKey]['sectionTotalMaterial'];
        }
        if (isset($unsetArray)) {
            foreach ($unsetArray as $sectionKey) {
                unset($sheetStructure['sections'][$sectionKey]);
            }
        }

        // Add boxStart values
        if (isset($sheetStructure['boxStart'])) {
            $sheetStructure['boxStart']['value'] = $this->getCellValue($sheet, $sheetStructure['boxStart']['firstCell']);
        }

        // Update the sheet_structure in the params array
        $cellValueB47 = $this->getCellValue($sheet, 'B47');
        $cellValueC47 = $this->getCellValue($sheet, 'C47');
        $cellValueE47 = $this->getCellValue($sheet, 'E47');
        $cellValueF47 = $this->getCellValue($sheet, 'F47');
        $params['sheet_structure'] = $sheetStructure;
        return $params;
    }

    protected function findEndOfLabour($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        for ($row = $highestRow; $row >= 1; $row--) {
            $cellValue = $sheet->getCell('B' . $row)->getValue();
            if (strtolower($cellValue) === 'работа') {
                return $row;
            }
        }
        return $highestRow; // Default to the last row if no labour found
    }

    protected function findOtherColumns($sheet)
    {
        $otherCols = [];
        $highestColumn = $sheet->getHighestColumn();
        $row = 1; // Assuming headers are in the first row

        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $sheet->getCell($col . $row)->getValue();
            switch (strtolower($cellValue)) {
                case 'тип':
                    $otherCols['type'] = $col;
                    break;
                case 'наименование':
                    $otherCols['name'] = $col;
                    break;
                case 'количество':
                    $otherCols['quantity'] = $col;
                    break;
                case 'ед. изм.':
                    $otherCols['unit'] = $col;
                    break;
                case 'цена':
                    $otherCols['price'] = $col;
                    break;
                case 'сумма':
                    $otherCols['total'] = $col;
                    break;
            }
        }

        return $otherCols;
    }

    protected function fillCustomChanges($sheet, $foundationParams)
    {
        //Additional lines
        if ($foundationParams['sheet_structure']['additionalLinesExist']) {
            $foundationParams['sheet_structure']['additionalLines'] = self::ADDITIONAL_LINES_DATA[$foundationParams['sheet_structure']['additionalLines']];
            foreach ($foundationParams['sheet_structure']['additionalLines']['dynamicCellSwaps'] as $key => $cell) {
                $foundationParams['sheet_structure']['additionalLines']['dynamicCellSwaps'][$key] = round($this->getCellValue($sheet, $cell), 0);
            }
        }
        
        //TitleOrSectionChangesPresent

        if ($foundationParams['sheet_structure']['hasDynamicSwaps']) {
            foreach ($foundationParams['sheet_structure']['dynamicSwaps'] as $key => $cell) {
                //dd ($key, $cell, $this->getCellValue($sheet, $cell));
                if (is_array($cell)) {
                    foreach ($cell as $subKey => $subCell) {
                        $foundationParams['sheet_structure']['dynamicSwaps'][$key][$subKey] = ($this->getCellValue($sheet, $subCell));
                    }
                } else {
                    $foundationParams['sheet_structure']['dynamicSwaps'][$key] = ($this->getCellValue($sheet, $cell));
                }
                if (is_numeric($foundationParams['sheet_structure']['dynamicSwaps'][$key])) {
                    $foundationParams['sheet_structure']['dynamicSwaps'][$key] = round($foundationParams['sheet_structure']['dynamicSwaps'][$key], 2);
                }
                
            }
            if ($foundationParams['sheet_structure']['hasTitleSwaps']) {
                //$foundationParams['sheet_structure']['title'];
                //dd($foundationParams['sheet_structure']['dynamicSwaps']);
                foreach ($foundationParams['sheet_structure']['dynamicSwaps'] as $key => $value) {
                    if (strpos($foundationParams['sheet_structure']['title'], $key) !== false) {
                        $foundationParams['sheet_structure']['title'] = str_replace($key, $value, $foundationParams['sheet_structure']['title']);
                    }
                }
                unset($foundationParams['sheet_structure']['titleSwaps']);
            }
            unset($foundationParams['sheet_structure']['hasTitleSwaps']);

            if ($foundationParams['sheet_structure']['hasSectionSwaps']) {
                foreach ($foundationParams['sheet_structure']['sectionSwaps'] as $sectionSwap) {
                    foreach ($foundationParams['sheet_structure']['sections'] as $sectionKey => $section) {
                        $compareAgainst = $sectionSwap['compare_against'];
                        if ($sectionKey === $compareAgainst) {
                            $changeTo = $sectionSwap['change_to'];
                            foreach ($foundationParams['sheet_structure']['dynamicSwaps'] as $key => $value) {
                                if (is_array($value)) {
                                    foreach ($value as $subKey => $subValue) {
                                        if (strpos($changeTo, $subKey) !== false) {
                                            $changeTo = str_replace($subKey, $subValue, $changeTo);
                                        }
                                    }
                                } else {
                                    if (strpos($changeTo, $key) !== false) {
                                        $changeTo = str_replace($key, $value, $changeTo);
                                    }
                                }
                            }
                            $foundationParams['sheet_structure']['sections'][$sectionKey]['value'] = $changeTo;
                        }
                    }
                }
                
                unset($foundationParams['sheet_structure']['sectionSwaps']);
            }
            unset($foundationParams['sheet_structure']['hasSectionSwaps']);
            unset($foundationParams['sheet_structure']['dynamicSwaps']);
        }
        unset($foundationParams['sheet_structure']['hasDynamicSwaps']);

        return $foundationParams;
    }

    protected function saveSpreadsheet($project)
    {
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $publicPath = storage_path('orders');
        $filename = $project->id . "_foundation_" . time() . ".xlsx";
        $outputPath = $publicPath . '/' . $filename;
        
        // Ensure the directory exists
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $writer->save($outputPath);

        Log::info('Foundation order file generated successfully: ' . $outputPath);
    }
    protected function getCellValue($sheet, $cell)
    {
        return $sheet->getCell($cell)->getCalculatedValue();
    }
}