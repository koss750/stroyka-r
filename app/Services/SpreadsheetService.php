<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use App\Models\Design;
use Illuminate\Support\Collection;
use App\Models\InvoiceType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class SpreadsheetService
{
    public $temp = 0;

    private $cellMappings;
    private $fVariationArray;
    private $plitaVariationArray;
    private $metalAndPlasticVariationArray;
    private $exceptionalSheetsArray;
    private $invoices;
    private $combinedSheetRows;
    private $additionalDeletedRows;
    private $totalBoxes;

    public function __construct()
    {
        $this->combinedSheetRows = [];
        $this->additionalDeletedRows = 0;
        $this->invoices = InvoiceType::all();
        $this->totalBoxes = [0, 0, 0, 0, 0, 0, 0];

        $this->cellMappings = [
            'fLenta' => [
                'D4' => 'lfLength',
                'D5' => 0.3,
                'D8' => 0.5,
                'D9' => 'lfAngleX',
                'D10' => 'lfAngleT',
                'D11' => 'lfAngleG',
                'D12' => 'lfAngle45',
                'D14' => 0.2,
                'D15' => 0.2,
                'D16' => 'mfSquare',
                'D18' => 'vfCount'
            ],
            'fVinta' => [
                'D44' => 'vfCount',
                'D45' => 'vfBalk',
                'D86' => 'outer_p',
                'D89' => 'lfAngleG'
            ],
            'brus' => [
                'C113' => 'baseD20RubF',
                'C114' => 'baseBalk1',
                'C115' => 'stolby'
            ],
            'brusFloors' => [
                'I112' => 'Sfl0',
                'I113' => 'Sfl1',
                'I114' => 'Sfl2',
                'I115' => 'Sfl3',
                'I116' => 'Sfl4'
            ],
            'ocb' => [
                'D169' => 'baseLength',
                'D170' => 'baseD20'
            ],
            'rSoft' => [
                'D283' => 'roofSquare',
                'D284' => 'srCherep',
                'D285' => 'srKover',
                'D286' => 'srKonK',
                'D287' => 'srMastika1',
                'D288' => 'srMastika',
                'D289' => 'mrKon',
                'D290' => 'srKonOneSkat',
                'D291' => 'srPlanVetr',
                'D292' => 'srPlanK',
                'D293' => 'srKapelnik',
                'D294' => 'mrEndv',
                'D295' => 'srGvozd',
                'D296' => 'mrSam70',
                'D297' => 'mrPack',
                'D298' => 'mrIzospanAM',
                'D299' => 'mrIzospanAM35',
                'D300' => 'mrLenta',
                'D301' => 'mrRokvul',
                'D302' => 'mrIzospanB',
                'D303' => 'mrIzospanB35',
                'D304' => 'mrPrimUgol',
                'D305' => 'mrPrimNakl',
                'D306' => 'srOSB',
                'D308' => 'srAero',
                'D309' => 'srAeroSkat',
                'D310' => 'stropValue'
            ],
            'pv' => [
                'D351' => 'pvPart1',
                'D352' => 'pvPart2',
                'D353' => 'pvPart3',
                'D354' => 'pvPart4',
                'D355' => 'pvPart5',
                'D356' => 'pvPart6',
                'D357' => 'pvPart7',
                'D358' => 'pvPart8',
                'D359' => 'pvPart9',
                'D360' => 'pvPart10',
                'D361' => 'pvPart11',
                'D362' => 'pvPart12',
                'D363' => 'pvPart13'
            ],
            'mv' => [
                'D367' => 'mvPart1',
                'D368' => 'mvPart2',
                'D369' => 'mvPart3',
                'D370' => 'mvPart4',
                'D371' => 'mvPart5',
                'D372' => 'mvPart6',
                'D373' => 'mvPart7',
                'D374' => 'mvPart8',
                'D375' => 'mvPart9',
                'D376' => 'mvPart10',
                'D377' => 'mvPart11',
                'D378' => 'mvPart12',
                'D379' => 'mvPart13'
            ],
            'srRoofSection' => [
                'L306' => 'srKonShir',
                'L307' => 'srKonOneSkat',
                'L311' => 'srEndn',
                'L312' => 'srEndv',
                'L313' => 'mrSam35',
                'L314' => 'srSam70',
                'L315' => 'srPack',
                'L316' => 'srIzospanAM',
                'L317' => 'srIzospanAM35',
                'L322' => 'srPrimUgol',
                'L323' => 'srPrimNakl'
            ]
        ];
        $this->fVariationArray = [
            ['600x300', 0.5, 0.3, 'fLenta600x300', 'fSVR600x300'],
            ['700x300', 0.6, 0.3, 'fLenta700x300', 'fSVR700x300'],
            ['800x300', 0.7, 0.3, 'fLenta800x300', 'fSVR800x300'],
            ['900x300', 0.8, 0.3, 'fLenta900x300', 'fSVR900x300'],
            ['1000x300', 0.9, 0.3, 'fLenta1000x300', 'fSVR1000x300'],
            ['600x400', 0.5, 0.4, 'fLenta600x400', 'fSVR600x400'],
            ['700x400', 0.6, 0.4, 'fLenta700x400', 'fSVR700x400'],
            ['800x400', 0.7, 0.4, 'fLenta800x400', 'fSVR800x400'],
            ['900x400', 0.8, 0.4, 'fLenta900x400', 'fSVR900x400'],
            ['1000x400', 0.9, 0.4, 'fLenta1000x400', 'fSVR1000x400'],
            ['600x500', 0.5, 0.5, 'fLenta600x500', 'fSVR600x500'],
            ['700x500', 0.6, 0.5, 'fLenta700x500', 'fSVR700x500'],
            ['800x500', 0.7, 0.5, 'fLenta800x500', 'fSVR800x500'],
            ['900x500', 0.8, 0.5, 'fLenta900x500', 'fSVR900x500'],
            ['1000x500', 0.9, 0.5, 'fLenta1000x500', 'fSVR1000x500'],
            ['700x600', 0.6, 0.6, 'fLenta700x600', 'fSVR700x600'],
            ['800x600', 0.7, 0.6, 'fLenta800x600', 'fSVR800x600'],
            ['900x600', 0.8, 0.6, 'fLenta900x600', 'fSVR900x600'],
            ['1000x600', 0.9, 0.6, 'fLenta1000x600', 'fSVR1000x600'],
        ];
        $this->plitaVariationArray = [
            ['0.2', 'fMono20'],
            ['0.25', 'fMono25'],
            ['0.3', 'fMono30'],
            ['0.35', 'fMono35'],
        ];
        $this->metalAndPlasticVariationArray = [
            ['Смета Мягкая', 'rSoftP', 'F92', 'K92'],
            ['Смета Мягкая', 'rSoftM', 'F77', 'K77'],
            ['Смета Мягкая', 'rSoftN', 'F92+F77', 'K92+K77'],
            ['Смета Железо', 'rMetalP', 'F111', 'K111'],
            ['Смета Железо', 'rMetalM', 'F96', 'K96'],
            ['Смета Железо', 'rMetalN', 'F111+F96', 'K111+K96'],
        ];
        $this->exceptionalSheetsArray = [
            'СВ-Рост', 'плита', 'лента', 'Железо', 'Мягкая'
        ];
    }
    private function checkIfSheetIsExceptional($sheetName) {
        foreach ($this->exceptionalSheetsArray as $exception) {
            if (strpos($sheetName, $exception) !== false) {
                return true;
            }
        }
        return false;
    }
    public function handle($filePath, $design, $multiple=false, $labour=true, $debug=1, $config=null) {
        try {
            $spreadsheet = IOFactory::createReader('Xlsx')->load($filePath);
        } catch (\Exception $e) {
            throw $e;
        }
        if ($config) {
            if ($config instanceof InvoiceType) {
                $newFilePath = $this->handleSingleSheet($spreadsheet, $design, $config);
            } else {
                $newFilePath = $this->processConfiguredSheets($spreadsheet, $design, $config);
            }
            return $newFilePath;
        }

        if ($multiple) {
            foreach ($design as $design) {
                $this->handlePriceIndexing($spreadsheet, $design);
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        Calculation::getInstance($spreadsheet)->clearCalculationCache();
        $this->processDatasheet($spreadsheet, $design);
        $filename = $design->id . "_" . time();
        $newFilePath = storage_path('app/public/orders/' . $filename . '.xlsx');
        $writer->save($newFilePath);

        return $newFilePath;
    }

    private function handleSingleSheet($spreadsheet, $design, $invoiceType)
    {
        // Get all InvoiceType data
        $sheetName = $invoiceType->sheetname;
        $spec = $invoiceType->sheet_spec;
        unset ($invoiceType->sheet_spec);

        $sheetArray[$invoiceType->oldestParent->label] = [$invoiceType->label, $invoiceType->sheetname, false, $invoiceType->title, $spec];
        
        // Prepare the template spreadsheet
        $this->prepareSpreadsheet($spreadsheet, $design);
        $sheet = $spreadsheet->getSheetByName($sheetName);
        
        // Create and replicate the Smeta
        $newSpreadsheet = $this->prepareNewSpreadsheet();
        $newSheet = $newSpreadsheet->getSheetByName('Смета');
        $this->processExceptionalSheets($spreadsheet, $sheetArray, $design);
        if ($invoiceType->oldestParent->label === 'd') {
            $this->adjustFloorsInSheet($sheet, $design->floorsNumber, $spec);
        }
        $this->copySingleSheet($sheet, $newSheet);
        return $this->saveNewSpreadsheet($newSpreadsheet, $design);
    }

    private function prepareNewSpreadsheet() {
        $newSpreadsheet = $this->createNewSpreadsheet();
        $newSpreadsheet->createSheet()->setTitle('Смета');
        return $newSpreadsheet;
    }

    private function getSheetsToCombine($config) {
        $sheetsToCombine = [];
        $extraCols = false;
        foreach ($config as $sheetName => $sheetConfig) {
            $sheet = $this->invoices->where('ref', $sheetConfig)->firstOrFail();
            $checkExtra = $this->checkExtraColumn($sheet->sheetname);
            $parentLabel = $sheet->oldestParent->label;
            if($sheet->label == "rNone" || $sheet->label == "fNone") {
                continue;
            }
            if($checkExtra) {
                $extraCols = true;
            }
            $sheetsToCombine[$parentLabel] = [$sheet->label, $sheet->sheetname, $checkExtra, $sheet->title, $sheet->sheet_spec];
        }
        $sheetsToCombine["extra"] = $extraCols;
        return $sheetsToCombine;
    }

    private function processConfiguredSheets($spreadsheet, $design, $config)
    {
        $this->prepareSpreadsheet($spreadsheet, $design);
        $newSpreadsheet = $this->createNewSpreadsheet();
        $sheetsToCombine = $this->getSheetsToCombine($config);
        
        $this->processExceptionalSheets($spreadsheet, $sheetsToCombine, $design);
        
        $newSheet = $this->combineSheets($spreadsheet, $newSpreadsheet, $sheetsToCombine, $design);
        $newSheet = $this->finalTouches($newSheet);
        
        return $this->saveNewSpreadsheet($newSpreadsheet, $design);
    }

    private function finalTouches($newSheet) {
        $single = false;
        if (sizeof($this->combinedSheetRows) == 1) {
            $single = true;
        }
        if (!$single) {
            $this->additionalDeletedRows += 7;
            $firstSmetaStart = $this->combinedSheetRows[0][1]['index_smeta_alt_start'];
            $firstSmetaEnd = $this->combinedSheetRows[0][1]['index_smeta_alt_end'];
            $lastSmetaStart = $this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][1]['index_smeta_alt_start'];
            $lastSmetaOffset = $this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][0];
            $lastSmetaSpecBoxCol = $this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][1]['index_total_start'][0];
            $lastSmetaSpecBoxRow = substr($this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][1]['index_total_start'], 1) + $lastSmetaOffset - $this->additionalDeletedRows;
            
            foreach ($this->totalBoxes as $index => $totalBox) {
                $newSheet->setCellValue($lastSmetaSpecBoxCol . ($lastSmetaSpecBoxRow + $index), $totalBox);
            }
            // go through cells in col A until lastsmetaSpecBoxRow and set the value to the totalBoxes
            for ($i = 1; $i <= $lastSmetaSpecBoxRow; $i++) {
                $newSheet->getStyle('A' . $i)->getNumberFormat()->setFormatCode('@');
            }
        }
        return $newSheet;
    }
    private function prepareSpreadsheet($spreadsheet, $design)
    {
        Calculation::getInstance($spreadsheet)->clearCalculationCache();
        $this->processDatasheet($spreadsheet, $design);
    }

    private function createNewSpreadsheet()
    {
        $newSpreadsheet = new Spreadsheet();
        $newSpreadsheet->removeSheetByIndex(0);
        return $newSpreadsheet;
    }

    private function processExceptionalSheets($spreadsheet, $sheetsToCombine, $design)
    {
        foreach ($sheetsToCombine as $sheetItem) {
            foreach ($this->exceptionalSheetsArray as $exception) {
                if (is_array($sheetItem)) {
                    if (strpos($sheetItem[1], $exception) !== false) {
                        $this->handleExceptionalSheet($spreadsheet, $exception, $sheetItem, $design);
                    }
                }
            }
        }
    }

    private function handleExceptionalSheet($spreadsheet, $exception, $sheetItem, $design)
    {
        switch ($exception) {
            case "СВ-Рост":
                $this->handleSVRost($spreadsheet, $sheetItem);
                break;
            case "плита":
                $this->handlePlita($spreadsheet, $sheetItem);
                break;
            case "лента":
                $this->handleLenta($spreadsheet, $sheetItem);
                break;
            case "Железо":
                $this->handleZhelezo($spreadsheet, $sheetItem, $design);
                break;
            case "Мягкая":
                $this->handleMyagkaya($spreadsheet, $sheetItem);
                break;
        }
    }

    private function handleSVRost($spreadsheet, $sheetItem)
    {
        $sheet = $spreadsheet->getSheetByName("data");
        foreach ($this->fVariationArray as $size) {
            if ($sheetItem[3] == $size[0]) {
                $sheet->setCellValue('D5', $size[2]);
                $sheet->setCellValue('D8', $size[1]);
            }
        }
    }

    private function handlePlita($spreadsheet, $sheetItem)
    {
        $sheet = $spreadsheet->getSheetByName("data");
        foreach ($this->plitaVariationArray as $size) {
            if ($sheetItem[0] == $size[1]) {
                $sheet->setCellValue('D87', $size[0]);
            }
        }
    }

    private function handleLenta($spreadsheet, $sheetItem)
    {
        $sheet = $spreadsheet->getSheetByName("data");
        foreach ($this->fVariationArray as $size) {
            if ($sheetItem[3] == $size[0]) {
                $sheet->setCellValue('D5', $size[2]);
                $sheet->setCellValue('D8', $size[1]);
            }
        }
    }

    private function handleZhelezo($spreadsheet, $sheetItem, $design)
    {
        $sheet = $spreadsheet->getSheetByName("Смета Железо");
        $sheet->getStyle('G41')->getFont()->setSize(12);
        $C3row = substr($sheet->getCell('C3')->getValue(), 2);
        $C3col = $sheet->getCell('C3')->getValue()[1];
        
        if ($sheetItem[3] == "Железо, пластик") {
            $startIndex = 97;
            $endRow = 112;
        } elseif ($sheetItem[3] == "Железо, метал") {
            $startIndex = 82;
            $endRow = 97;
        } elseif ($sheetItem[3] == "Железо, без водостока") {
            $startIndex = 82;
            $endRow = 112;
        } else {
            throw new Exception("Неизвестный тип водостока");
        }
        
        $rowsToDelete = $endRow - $startIndex + 1;
        $sheet->removeRow($startIndex, $rowsToDelete);
        $this->additionalDeletedRows += $rowsToDelete;
        $metaList = $design->metaList;
        $size = count($metaList);
        $startIndex = 42;
        $endIndex = 63;
        $rowsToDelete += $endIndex - $startIndex - $size + 1;
        $firstRowToDelete = $startIndex + $size;
        $sheet->removeRow($firstRowToDelete, $rowsToDelete);
        $this->additionalDeletedRows += $rowsToDelete;
        $C3row -= $rowsToDelete;
        $sheet->setCellValue('C3', "r" . $C3col . $C3row);

        // Adjust sums
        $this->adjustSums($sheet, $sheetItem[3]);
    }

    private function handleMyagkaya($spreadsheet, $sheetItem)
    {
        $sheet = $spreadsheet->getSheetByName("Смета Мягкая");
        $C3row = substr($sheet->getCell('C3')->getValue(), 2);
        $C3col = $sheet->getCell('C3')->getValue()[1];
        
        if ($sheetItem[3] == "Мягкая, пластик") {
            $startIndex = 78;
            $endRow = 93;
        } elseif ($sheetItem[3] == "Мягкая, метал") {
            $startIndex = 63;
            $endRow = 78;
        } elseif ($sheetItem[3] == "Мягкая, без водостока") {
            $startIndex = 63;
            $endRow = 93;
        } else {
            throw new Exception("Неизвестный тип водостока");
        }
        
        $rowsToDelete = $endRow - $startIndex + 1;
        $sheet->removeRow($startIndex, $rowsToDelete);
        $this->additionalDeletedRows += $rowsToDelete;
        
        $C3row -= $rowsToDelete;
        $sheet->setCellValue('C3', "r" . $C3col . $C3row);

        // Adjust sums
        $this->adjustSums($sheet, $sheetItem[3]);
    }

    private function adjustSums($sheet, $gutterType)
    {
        // Assuming the sums are in columns C, D, and E
        $lastRow = $sheet->getHighestRow();
        $sumC = 0;
        $sumD = 0;
        $sumE = 0;

        for ($i = 1; $i <= $lastRow; $i++) {
            $sumC += is_numeric($sheet->getCell("C$i")->getValue()) ? $sheet->getCell("C$i")->getValue() : 0;
            $sumD += is_numeric($sheet->getCell("D$i")->getValue()) ? $sheet->getCell("D$i")->getValue() : 0;
            $sumE += is_numeric($sheet->getCell("E$i")->getValue()) ? $sheet->getCell("E$i")->getValue() : 0;
        }

        // Update the sum cells (adjust these cell references as needed)
        $sheet->setCellValue('C' . ($lastRow + 1), $sumC);
        $sheet->setCellValue('D' . ($lastRow + 1), $sumD);
        $sheet->setCellValue('E' . ($lastRow + 1), $sumE);
    }

    private function combineSheets($spreadsheet, $newSpreadsheet, $sheetsToCombine, $design)
    {
        $newSheet = $newSpreadsheet->createSheet(0);
        $newSheet->setTitle("Смета");
        $newSheetRow = 1;
        $totalRowOffset = 0;
        $sumsSection = [];
        $extraCol = $sheetsToCombine["extra"] ?? false;
        unset($sheetsToCombine["extra"]);
        
        $sortedSheets = $this->sortSheets($sheetsToCombine);
        foreach ($sortedSheets as $sheetIndex => $sheet) {
            $sheetTitle = $sheet[1];
            $skipCol = $sheet[2];
            $invoiceType = InvoiceType::where('sheetname', $sheetTitle)->firstOrFail();
            $spec = $invoiceType->sheet_spec;

            $sheet = $spreadsheet->getSheetByName($sheetTitle);

            if ($spreadsheet->sheetNameExists($sheetTitle)) {
                $startRow = $newSheetRow;
                
                if ($invoiceType->oldestParent->label === 'd') {
                    $this->adjustFloorsInSheet($sheet, $design->floorsNumber, $spec);
                }
                
                $this->copySheetContent($sheet, $newSheet, $sheetIndex, $spec, $skipCol, $extraCol, $newSheetRow);
                $totalRowOffset += $newSheetRow - $startRow;
                $sumsSection = $this->updateRunningTotals($sheet, $spec, $sumsSection);

                // Add section total
                $this->addSectionTotal($newSheet, $newSheetRow, $spec);

                if ($sheetIndex == count($sortedSheets) - 1) {
                    $this->setRunningTotals($newSheet, $spec, $sumsSection, $totalRowOffset);
                }
            }
        }

        return $newSheet;
    }

    private function sortSheets($sheetsToCombine)
    {
        $foundation = null;
        $domokomplekt = null;
        $roof = null;
        $others = [];

        foreach ($sheetsToCombine as $key => $sheet) {
            $invoiceType = InvoiceType::where('sheetname', $sheet[1])->firstOrFail();
            $label = $invoiceType->label;

            if (strpos($label, 'f') === 0) {
                $foundation = [0 => $sheet];
            } elseif (strpos($label, 'd') === 0) {
                $domokomplekt = [1 => $sheet];
            } elseif (strpos($label, 'r') === 0) {
                $roof = [2 => $sheet];
            }
        }

        $sortedSheets = array_merge(
            $foundation ?? [],
            $domokomplekt ?? [],
            $roof ?? [],
            $others
        );

        return $sortedSheets;
    }

    private function adjustFloorsInSheet($sheet, $floorsNumber, &$spec)
    {
        $floorSections = [
            'floor1' => ['start' => null, 'end' => null],
            'floor2' => ['start' => null, 'end' => null],
            'floor3' => ['start' => null, 'end' => null],
        ];

        // Find the start and end rows for each floor section
        foreach ($spec['sections'] as $section) {
            if (strpos($section['title'], 'floor1') !== false) {
                $floorSections['floor1']['start'] = $section['start'];
                $floorSections['floor1']['end'] = $section['end'] ?? null;
            } elseif (strpos($section['title'], 'floor2') !== false) {
                $floorSections['floor2']['start'] = $section['start'];
                $floorSections['floor2']['end'] = $section['end'] ?? null;
            } elseif (strpos($section['title'], 'floor3') !== false) {
                $floorSections['floor3']['start'] = $section['start'];
                $floorSections['floor3']['end'] = $section['end'] ?? null;
            }
        }

        // Determine which floors to remove
        $floorsToRemove = [];
        if ($floorsNumber < 3) {
            $floorsToRemove[] = 'floor3';
        }
        if ($floorsNumber < 2) {
            $floorsToRemove[] = 'floor2';
        }

        // Remove unnecessary floor sections
        $totalRowsRemoved = 0;
        foreach ($floorsToRemove as $floorToRemove) {
            if ($floorSections[$floorToRemove]['start'] !== null) {
                $startRow = $floorSections[$floorToRemove]['start'];
                $endRow = $floorSections[$floorToRemove]['end'] ?? $sheet->getHighestRow();
                $rowsToRemove = $endRow - $startRow + 1;
                $sheet->removeRow($startRow, $rowsToRemove);
                $totalRowsRemoved += $rowsToRemove;

                // Adjust the end row for the current section
                if (isset($section['end']) && $section['end'] > $startRow) {
                    $section['end'] = min($section['end'], $startRow - 1);
                }

                // Adjust the overall end of the sheet
                if ($spec['index_smeta_alt_end'] > $startRow) {
                    $spec['index_smeta_alt_end'] -= $rowsToRemove;
                }

                // Ensure no section end exceeds the new sheet end
                foreach ($spec['sections'] as &$section) {
                    if (isset($section['end'])) {
                        $section['end'] = min($section['end'], $spec['index_smeta_alt_end']);
                    }
                }
            }
        }
    }

    private function copySingleSheet($sourceSheet, $targetSheet)
    {
        // Copy column widths
        foreach (range('A', 'N') as $col) {
            $targetSheet->getColumnDimension($col)->setWidth(
                $sourceSheet->getColumnDimension($col)->getWidth()
            );
        }

        // Get the highest row and column of the source sheet
        $highestRow = $sourceSheet->getHighestRow();
        $highestColumn = $sourceSheet->getHighestColumn();

        // Copy cell contents and styles
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sourceSheet->getCell($col . $row)->getCalculatedValue();
                $targetSheet->setCellValue($col . $row, $cellValue);
                $targetSheet->getStyle($col . $row)->applyFromArray(
                $sourceSheet->getStyle($col . $row)->exportArray()
                );
            }
        }

        // Copy merged cells
        foreach ($sourceSheet->getMergeCells() as $mergeCell) {
            $targetSheet->mergeCells(str_replace('1', $row - $highestRow, $mergeCell));
        }
    }

    private function copySheetContent($sheet, $newSheet, $sheetIndex, $spec, $skipCol, $extraCol, &$newSheetRow)
    {
        if ($sheetIndex == 0) {
            $startRow = 1;
            $newSheetRow = 1; // Reset for the first sheet
        } else {
            $startRow = $spec['index_smeta_alt_start'];
        }
        $endRow = $spec['index_smeta_alt_end'];
        $this->combinedSheetRows[$sheetIndex] = [$newSheetRow, $spec];
        if(!$skipCol) {
            //insert new col before C and H
            $sheet->insertNewColumnBefore('C', 1);
            $sheet->insertNewColumnBefore('I', 1);
            for ($row = $startRow; $row <= $endRow; $row++) {
                $sheet->mergeCells('B' . $row . ':C' . $row);
                $sheet->mergeCells('H' . $row . ':I' . $row);
            }
        }
        // Copy column widths
        foreach (range('A', 'N') as $col) {
            $newSheet->getColumnDimension($col)->setWidth(
                $sheet->getColumnDimension($col)->getWidth()
            );
        }

        for ($row = $startRow; $row <= $endRow; $row++) {
            $newSheetCol = 'A';
            for ($col = 'A'; $col <= 'N'; $col++) {
                //log::info("handling " . $sheet->getTitle() . " column copying. skipcol is " . $skipCol . " and col is " . $col);
            
                $cellValue = $sheet->getCell($col . $row)->getCalculatedValue();
                $newSheet->setCellValue($newSheetCol . $newSheetRow, $cellValue);
                $newSheet->getStyle($newSheetCol . $newSheetRow)->applyFromArray(
                    $sheet->getStyle($col . $row)->exportArray()
                );
                $newSheetCol++;
            }
            $newSheetRow++;
        }

        $this->copyMergedCells($sheet, $newSheet, $sheetIndex, $startRow, $endRow, $newSheetRow - ($endRow - $startRow + 1));

        //$this->addSectionHeaders($newSheet, $spec, $sheetIndex, $newSheetRow - ($endRow - $startRow + 1));
    }

    private function copyMergedCells($sheet, $newSheet, $sheetIndex, $startRow, $endRow, $rowOffset)
    {
        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mergeCellRange = Coordinate::extractAllCellReferencesInRange($mergeCell);
            $firstCell = $mergeCellRange[0];
            $lastCell = $mergeCellRange[count($mergeCellRange) - 1];
            
            $firstColumn = Coordinate::columnIndexFromString(Coordinate::coordinateFromString($firstCell)[0]);
            $lastColumn = Coordinate::columnIndexFromString(Coordinate::coordinateFromString($lastCell)[0]);
            
            $firstRow = Coordinate::coordinateFromString($firstCell)[1];
            $lastMergeRow = Coordinate::coordinateFromString($lastCell)[1];
            
            if ($firstRow >= $startRow && $lastMergeRow <= $endRow) {
                $newFirstRow = $firstRow - $startRow + $rowOffset;
                $newLastRow = $lastMergeRow - $startRow + $rowOffset;
                
                $newMergeRange = Coordinate::stringFromColumnIndex($firstColumn) . $newFirstRow . ':' . 
                                 Coordinate::stringFromColumnIndex($lastColumn) . $newLastRow;
                $newSheet->mergeCells($newMergeRange);
            }
        }
    }

    private function handleExtraColumns($sheet, $newSheet, $startRow, $endRow)
    {
        for ($row = $startRow; $row <= $endRow; $row++) {
            $newSheet->mergeCells('B' . $row . ':C' . $row);
            $newSheet->mergeCells('H' . $row . ':I' . $row);
        }
    }

    private function updateRunningTotals($sheet, $spec, $sumsSection)
    {
        $totalsStart = $spec['index_total_start'];
        foreach ($spec['total'] as $index => $formula) {
            try {
                $cellReference = $this->formatCellReference($totalsStart, $index);
                $cellValue = $sheet->getCell($cellReference)->getCalculatedValue();
                
                if ($cellValue === null || $cellValue === '') {
                    // Try an alternative cell reference
                    $cellReference = $this->formatCellReference($totalsStart, $index, -1);
                    $cellValue = $sheet->getCell($cellReference)->getCalculatedValue();
                }
                
                if ($cellValue === null || $cellValue === '') {
                    // If still null, try one more alternative
                    $cellReference = $this->formatCellReference($totalsStart, $index, 0, $this->additionalDeletedRows);

                    $cellValue = $sheet->getCell($cellReference)->getCalculatedValue();
                }
                
                $sumsSection[$index] = is_numeric($cellValue) ? (int)$cellValue : 0;
                $this->totalBoxes[$index] += $sumsSection[$index];
            } catch (\PhpOffice\PhpSpreadsheet\Exception\Exception $e) {
                // Log the error and continue with the next cell
                Log::error("Error processing cell {$cellReference}: " . $e->getMessage());
                $sumsSection[$index] = 0;
            }
        }
        return $sumsSection;
    }

    private function formatCellReference($baseReference, $rowOffset = 0, $columnOffset = 0, $additionalRowOffset = 0)
    {
        $column = $this->getColumnLetter($baseReference, $columnOffset);
        $row = $this->getRowNumber($baseReference) + $rowOffset + $additionalRowOffset;
        return $column . $row;
    }

    private function getColumnLetter($cellReference, $offset = 0)
    {
        $column = preg_replace('/[0-9-]/', '', $cellReference);
        $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($column) + $offset;
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
    }

    private function getRowNumber($cellReference)
    {
        return (int)preg_replace('/[A-Za-z-]/', '', $cellReference);
    }

    private function setRunningTotals($newSheet, $spec, $sumsSection, $totalRowOffset)
    {
        $this->additionalDeletedRows += 7;
        $lastSmetaStart = $this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][1]['index_smeta_alt_start'];
        $lastSmetaOffset = $this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][0];
        $lastSmetaSpecBoxRow = substr($this->combinedSheetRows[sizeof($this->combinedSheetRows) - 1][1]['index_total_start'], 1) + $lastSmetaOffset - $this->additionalDeletedRows;
        $totalsStart = $spec['index_total_start'];
        foreach ($this->totalBoxes as $index => $value) {
            $cellReference = $this->getColumnLetter($totalsStart) . ($lastSmetaSpecBoxRow+$index);
            if ($newSheet->getCell($cellReference)->getCalculatedValue() == null) {
                $newSheet->setCellValue($cellReference, $value);
            } else {
                $newSheet->setCellValue($this->getColumnLetter($totalsStart, -1) . ($lastSmetaSpecBoxRow+$index), $value);
            }
            //dd($this->getColumnLetter($totalsStart, -2) . ($lastSmetaSpecBoxRow+$index));
        }
    }

    private function adjustFormulaRowNumbers($formula, $offset)
    {
        return preg_replace_callback('/([A-Z])(\d+)/', function($matches) use ($offset) {
            return $matches[1] . ($matches[2] + $offset);
        }, $formula);
    }

    private function saveNewSpreadsheet($newSpreadsheet, $design)
    {
        $writer = IOFactory::createWriter($newSpreadsheet, 'Xlsx');
        $filename = $design->id . "_" . time() . "_configured";
        $newFilePath = storage_path('app/public/orders/' . $filename . '.xlsx');
        $writer->save($newFilePath);
        return $newFilePath;
    }

    private function addSectionHeaders($newSheet, $spec, $sheetIndex, $rowOffset)
    {
        if (!isset($spec['sections'])) {
            return;
        }

        foreach ($spec['sections'] as $section) {
            $sectionStart = $section['start'];
            if ($sheetIndex > 0) {
                $sectionStart -= $spec['index_smeta_alt_start'] - 1;
            }
            $newRowNumber = $sectionStart + $rowOffset + 1; // Add 1 to correct the row number

            // Merge cells A-F for the section header
            $newSheet->mergeCells("A{$newRowNumber}:F{$newRowNumber}");

            // Set alignment to left
            $newSheet->getStyle("A{$newRowNumber}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Set the section title
            $newSheet->setCellValue("A{$newRowNumber}", $section['title']);
        }
    }

    private function addSectionTotal($newSheet, &$newSheetRow, $spec)
    {
        // Add 3 empty rows
        for ($i = 0; $i < 3; $i++) {
            $newSheetRow++;
        }

        // Add "Total for this section" row
        $newSheet->setCellValue("A$newSheetRow", "Итого по разделу:");
        $newSheet->mergeCells("A$newSheetRow:C$newSheetRow");
        
        // Calculate total for the section
        $startRow = $spec['index_smeta_alt_start'];
        $endRow = $spec['index_smeta_alt_end'];
        $totalFormula = "=SUM(D$startRow:D$endRow)";
        $newSheet->setCellValue("D$newSheetRow", $totalFormula);

        // Apply styling
        $newSheet->getStyle("A$newSheetRow:D$newSheetRow")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
        ]);

        $newSheetRow++;
    }

private function checkExtraColumn($sheet) {
    $sheetsWithExtraCol = ["плита", "лента", "СВ-Рост"];
    foreach ($sheetsWithExtraCol as $exception) {
        if (strpos($sheet, $exception) !== false) {
            return true;
        }
    }
    return false;
    }

    public function handlePriceIndexing($spreadsheet, $design)
    {
         // Reset calculation cache
        $dangerous = 0;
        Calculation::getInstance($spreadsheet)->clearCalculationCache();
        Log::info("Cleared calculation cache and now processing data for " . $design->id);
        $this->processDatasheet($spreadsheet, $design);
        $minimum = 0;
        $exceptionalSheets = ["Мягкая", "Железо", "плита", "лента", "СВ-Рост"];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            if (strpos($worksheet->getTitle(), "Смета") !== false) {
                $invoiceType = $this->invoices->where('sheetname', $worksheet->getTitle())->first();
                if ($invoiceType != null) {
                    $config = $invoiceType->sheet_spec;
                    $materialCostFormula = $config['total'][4];
                    $worksheet->setCellValue("C4", $materialCostFormula);
                } else {
                    Log::info("Invoice not found for " . $worksheet->getTitle());
                }
                $continue = false;
                if ($this->temp == 0 && (strpos($worksheet->getTitle(), "КС 145х45") !== false || strpos($worksheet->getTitle(), "КС 145x45") !== false)) {
                    //delete all columns beyond N
                    Log::info("Deleting columns beyond N");
                    $worksheet->removeColumn("N", 18250);
                    $this->temp = 1;
                }
                foreach ($exceptionalSheets as $exception) {
                    if (strpos($worksheet->getTitle(), $exception) !== false) {
                        $continue = true;
                        switch ($exception) {
                            case "лента":
                                $this->processLenta($spreadsheet, $design);
                                break;
                            case "СВ-Рост":
                                $this->processSVR($spreadsheet, $design);
                                break;
                            case "Железо":
                                $this->metalAndPlastic($spreadsheet, $design);
                                break;
                            case "плита":
                                $this->processPlate($spreadsheet, $design);
                                break;
                        }
                    }
                }
                if (!$continue) {
                    $variation = str_replace("Смета ", "", $worksheet->getTitle());
                    $variation_ref = Redis::get($worksheet->getTitle());
                    $labour = $worksheet->getCell("C3")->getCalculatedValue();
                    $material = $worksheet->getCell("C4")->getCalculatedValue();
                    $total = $worksheet->getCell("C5")->getCalculatedValue();
                    $material = is_numeric($material) && !is_nan($material) ? round($material, 0) : 999;
                    $labour = is_numeric($labour) && !is_nan($labour) ? round($labour, 0) : 999;
                    $total = is_numeric($total) && !is_nan($total) ? round($total, 0) : 999;
                    

                    $results[$design->id][$variation] = [
                        "labour" => $labour,
                        "material" => $material,
                        "total" => $total,
                    ];

                    if ($material == 999 || $labour == 999 || $total == 999) {
                        $dangerous = 1;
                    }
                    if (strpos($design->title, "ОЦБ") !== false && $variation == 'ХВ 200') {
                        Log::info("project " . $design->id . " variation " . $variation . " labour " . $labour . " material " . $material . " total " . $total);
                        Redis::set($design->id, json_encode($results[$design->id][$variation]['material']));
                    }
                    if (strpos($design->title, "ПБ") !== false && $variation == 'брус ЕВ 145х140') {
                        Redis::set($design->id, json_encode($results[$design->id][$variation]['material']));
                    }
                    if (strpos($design->title, "ОЦБ") !== false && $variation == 'ХВ 180') {
                        Redis::set($design->id . "_seasonal", json_encode($results[$design->id][$variation]['material']));
                    }
                    if (strpos($design->title, "ПБ") !== false && $variation == 'брус ЕВ 145х140') {
                        Redis::set($design->id . "_seasonal", json_encode($results[$design->id][$variation]['material']));
                    }

                    // Add records to Redis
                    $redisKey = $design->id . "_" . $variation_ref;
                    Redis::set($redisKey, json_encode($results[$design->id][$variation]));
                }
                if ($dangerous == 1) {
                    $design->mMetrics = 1;
                    $design->save();
                } else {
                    $design->mMetrics = 0;
                }
                $design->save();
            }
        }
        $zeroCostSheets = ["fNone", "rNone"];
        foreach ($zeroCostSheets as $sheet) {
         $redisKey = $design->id . "_" . $sheet;
         Redis::set($redisKey, json_encode(["labour" => 0, "material" => 0, "total" => 0]));
        }
    }

    public function processLenta($spreadsheet, $design) {
        foreach ($this->fVariationArray as $variation) {
            Calculation::getInstance($spreadsheet)->clearCalculationCache();
            $worksheet = $spreadsheet->getSheetByName('data');
            $worksheet->setCellValue("D8", $variation[1]);
            $worksheet->setCellValue("D5", $variation[2]);
            $worksheet = $spreadsheet->getSheetByName('Смета лента 600х300');
            $labour = $worksheet->getCell("C3")->getCalculatedValue();
            $material = $worksheet->getCell("C4")->getCalculatedValue();
            $total = $worksheet->getCell("C5")->getCalculatedValue();
            
            $material = is_numeric($material) && !is_nan($material) ? round($material, 0) : 999;
            $labour = is_numeric($labour) && !is_nan($labour) ? round($labour, 0) : 999;
            $total = is_numeric($total) && !is_nan($total) ? round($total, 0) : 999;

            $result = [
                "labour" => $labour,
                "material" => $material,
                "total" => $total,
            ];

            // Add records to Redis
            $redisKey = $design->id . "_" . $variation[3];
            Redis::set($redisKey, json_encode($result));
        }
    }

    public function processSVR($spreadsheet, $design) {
        foreach ($this->fVariationArray as $variation) {
            Calculation::getInstance($spreadsheet)->clearCalculationCache();
            $worksheet = $spreadsheet->getSheetByName('data');
            $worksheet->setCellValue("D8", $variation[1]);
            $worksheet->setCellValue("D5", $variation[2]);
            $worksheet = $spreadsheet->getSheetByName('Смета СВ-Рост 600х300');
            $labour = $worksheet->getCell("C3")->getCalculatedValue();
            $material = $worksheet->getCell("C4")->getCalculatedValue();
            $total = $worksheet->getCell("C5")->getCalculatedValue();

            $material = is_numeric($material) && !is_nan($material) ? round($material, 0) : 999;
            $labour = is_numeric($labour) && !is_nan($labour) ? round($labour, 0) : 999;
            $total = is_numeric($total) && !is_nan($total) ? round($total, 0) : 999;

            $result = [
                "labour" => $labour,
                "material" => $material,
                "total" => $total,
            ];

            // Add records to Redis
            $redisKey = $design->id . "_" . $variation[4];
            Redis::set($redisKey, json_encode($result));
        }
    }

    public function processPlate($spreadsheet, $design) {
        $variation = $this->plitaVariationArray;
        
        foreach ($variation as $variation) {
            Calculation::getInstance($spreadsheet)->clearCalculationCache();
            $worksheet = $spreadsheet->getSheetByName('data');
            $worksheet->setCellValue("D87", $variation[0]);
        $worksheet = $spreadsheet->getSheetByName('Смета плита');
        $labour = $worksheet->getCell("C3")->getCalculatedValue();
        $material = $worksheet->getCell("C4")->getCalculatedValue();
        $total = $worksheet->getCell("C5")->getCalculatedValue();

        $material = is_numeric($material) && !is_nan($material) ? round($material, 0) : 999;
            $labour = is_numeric($labour) && !is_nan($labour) ? round($labour, 0) : 999;
            $total = is_numeric($total) && !is_nan($total) ? round($total, 0) : 999;

            $result = [
                "labour" => $labour,
                "material" => $material,
                "total" => $total,
        ];

            // Add records to Redis
            $redisKey = $design->id . "_" . $variation[1];
            Redis::set($redisKey, json_encode($result));
        }
    }

    public function metalAndPlastic($spreadsheet, $design) {
        $variation = $this->metalAndPlasticVariationArray;
        foreach ($variation as $variation) {
            $worksheet = $spreadsheet->getSheetByName($variation[0]);
            Calculation::getInstance($spreadsheet)->clearCalculationCache();
            $labour = $worksheet->getCell("C3")->getCalculatedValue();
            $labourCell = $worksheet->getCell("C3")->getValue();
            $labourCell = str_replace('=', '', $labourCell);
            
            $material = $worksheet->getCell("C4")->getCalculatedValue();
            $materialCell = $worksheet->getCell("C4")->getValue();
            $materialCell = str_replace('=', '', $materialCell);
            
            // Handle deductions
            $deduction1 = $this->calculateDeduction($worksheet, $variation[2]);
            $deduction2 = $this->calculateDeduction($worksheet, $variation[3]);
            
            try {
                $total = $worksheet->getCell("C5")->getCalculatedValue();
                $material = $material - $deduction2;
                $labour = $labour - $deduction1;
                $total = $total - $deduction1 - $deduction2;
            } catch (\TypeError $e) {
                $total = 999;
            }
            

            $material = is_numeric($material) && !is_nan($material) ? round($material, 0) : 999;
            $labour = is_numeric($labour) && !is_nan($labour) ? round($labour, 0) : 999;
            $total = is_numeric($total) && !is_nan($total) ? round($total, 0) : 999;

            $result = [
                "labour" => $labour,
                "material" => $material,
                "total" => $total,
            ];

            // Add records to Redis
            $redisKey = $design->id . "_" . $variation[1];
            Redis::set($redisKey, json_encode($result));
        }
    }

    private function calculateDeduction($worksheet, $cellReference) {
        if (strpos($cellReference, '+') !== false) {
            $cells = explode('+', $cellReference);
            $deduction = 0;
            foreach ($cells as $cell) {
                $deduction += $worksheet->getCell($cell)->getCalculatedValue();
            }
            return $deduction;
        } else {
            return $worksheet->getCell($cellReference)->getCalculatedValue();
        }
    }

    private function clearDatasheet($sheet) {
        foreach ($this->cellMappings as $section => $cells) {
            foreach ($cells as $cell => $value) {
                $sheet->setCellValue($cell, 0);
            }
        }
    }

    public function processDatasheet($spreadsheet, $design)
    {
        $sheet = $spreadsheet->getSheetByName("data");
        $this->clearDatasheet($sheet);
         //Фундамент лента
         foreach ($this->cellMappings['fLenta'] as $cell => $value) {
             $sheet->setCellValue($cell, is_string($value) ? $design->$value : $value);
         }
         //Фундамент Винта
        foreach ($this->cellMappings['fVinta'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

        //Брус
         
        foreach ($this->cellMappings['brus'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

        // floor areas for Brus
        $allFloors = $design->areafl0[0];
        foreach ($this->cellMappings['brusFloors'] as $cell => $key) {
            if (is_numeric($allFloors[$key])) {
                $sheet->setCellValue($cell, $allFloors[$key]);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

        // OCB and stuff
        foreach ($this->cellMappings['ocb'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }
        Log::info("Updated OCB section of Data");

        // Кровля мягкая
        foreach ($this->cellMappings['rSoft'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }
        Log::info("Updated rSoft section of Data");

        // PV parts
        foreach ($this->cellMappings['pv'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

        // MV parts
        foreach ($this->cellMappings['mv'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

        // srRoofSection
        foreach ($this->cellMappings['srRoofSection'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }
        Log::info("Updated srRoofSection section of Data");

        $startingIndex = 281;
        $endingIndex = 302;
        foreach ($design->metaList as $metal) {
            $sheet->setCellValue('L' . $startingIndex, $metal['width']);
            $sheet->setCellValue('M' . $startingIndex, $metal['quantity']);
            $startingIndex++;
        }
        for ($i = $startingIndex; $i <= $endingIndex; $i++) {
            $sheet->setCellValue('L' . $i, 0);
            $sheet->setCellValue('M' . $i, 0);
        }

        Log::info("Moving to Balki");
        $sheet = $spreadsheet->getSheetByName('балки');
        $startingIndex = 15;
        $endingIndex = 40;
        // Mapping of floor names to numbers/letters
        $floorMapping = [
            "Первый" => '1', // ервый
            "Второй" => '2', // Второй
            "Третий" => '3', // Третий
            "Чердак" => 'Ч'  // Чердак
        ];
        $uniqueLengths = [];
        foreach ($design->floorsList as $room) {
            $floorNumber = $floorMapping[$room['floors']] ?? ''; 
            $sheet->setCellValue('E' . $startingIndex, $room['length']);
            if (!in_array($room['length'], $uniqueLengths)) {
                $uniqueLengths[] = $room['length'];
            }
            $sheet->setCellValue('F' . $startingIndex, $room['width']);
            $sheet->setCellValue('G' . $startingIndex, 630);
            $sheet->setCellValue('H' . $startingIndex, $floorNumber);
            $startingIndex++;
        }

        for ($i = $startingIndex; $i <= $endingIndex; $i++) {
            $sheet->setCellValue('E' . $i, "");
            $sheet->setCellValue('F' . $i, "");
            $sheet->setCellValue('G' . $i, "");
            $sheet->setCellValue('H' . $i, "");
        }
        Calculation::getInstance($spreadsheet)->clearCalculationCache();
        $startingIndex = 15;
        $endingIndex = 40;
        foreach ($uniqueLengths as $length) {
            $sheet->setCellValue('P' . $startingIndex, $length);
            $startingIndex++;
        }
        for ($i = $startingIndex; $i <= $endingIndex; $i++) {
            $sheet->setCellValue('P' . $i, "");
        }

        $sheet->setCellValue('P15', "=UNIQUE(E15:E40)");

        Log::info("Balki section completed");
        return $spreadsheet;
    }
}