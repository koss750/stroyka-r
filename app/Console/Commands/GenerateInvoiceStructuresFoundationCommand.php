<?php

namespace App\Console\Commands;

use App\Models\Foundation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use Exception;

class GenerateInvoiceStructuresFoundationCommand extends Command
{
    protected $signature = 'app:structures-f {foundation_id? : The ID of the Foundation to process}';
    protected $description = 'Generate foundation structures for one or all Foundations';

    public $decimalPlaces = [
        'material' => [
            0 => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'п.м.', 'м/см', 'смена'],
            2 => ['м2'],
            3 => ['м3', 'кг', 'тн'],
        ],
        'labour' => [
            0 => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'м/см', 'смена'],
            1 => ['п.м.'],
            2 => ['м2'],
            3 => ['м3', 'кг', 'тн'],
        ],
    ];

    public $replacementArray = [
        'к-кт' => 'к-т',
        'пач.' => 'пачка',
        'уп' => 'уп.',
        'м.п.' => 'п.м.',
        'м.п' => 'п.м.',
        'п.м' => 'п.м.',
        'упак' => 'уп.',
        'тн.' => 'тн',
        'см' => 'смена'
    ];

    public $format = [
            0 => "0",
            1 => "0.0",
            2 => "0.00",
            3 => "0.000"
    ];

    public $subHeadings = [
        1 => ["partial" => "к повороту канал"],
        2 => ["full" => "Расходные материалы "]
    ];

    public $subHeadingParams = [
        1 => ["entireRow" => true],
        2 => ["entireRow" => true]
    ];


    public function handle()
    {
        $foundationId = $this->argument('foundation_id');
        $query = Foundation::query();
        if ($foundationId) {
            $query->where('id', $foundationId);
        }
        $foundations = $query->get();


        foreach ($foundations as $foundation) {
            $this->processFoundation($foundation);
        }

        $this->info('Foundation structures generated successfully.');
    }

    public function checkForTitleException($foundation) {
        
        $exceptions = $foundation->exceptions;
        if (isset($exceptions['title'])) {
            $title = str_replace('{original}', $foundation->custom_order_title, $exceptions['title']);
            return $title;
        }
        return $foundation->custom_order_title;
    }

    public function checkForSectionTitleException($foundation, $section) {


        if (!isset($foundation->exceptions['sectionTitle'])) {
            return false;
        } else {
            if (is_array($foundation->exceptions['sectionTitle'])) {
                foreach ($foundation->exceptions['sectionTitle'] as $sectionTitle) {
                    if ($section === $sectionTitle['compare_against'])
                    {
                        return $sectionTitle['change_to'];
                    }
                }
                return false;
            } else {
                if ($section === $foundation->exceptions['sectionTitle']['compare_against'])
                {
                    return $foundation->exceptions['sectionTitle']['change_to'];
                } else return false;
            }
        }
    }

    public function checkForAdditionalRowsAfterTitle($foundation) {
        if (isset($foundation->exceptions['additionalLines'])) {
            return true;
        }
        return false;
    }

    private function processFoundation(Foundation $foundation)
    {
        $this->info('Processing foundation: ' . $foundation->title);
        $parameters['sheet_structure'] = [];
        $spreadsheet = IOFactory::createReader('Xlsx')->load(storage_path('templates/foundation/' . $foundation->template_path));
        
        Calculation::getInstance($spreadsheet)->clearCalculationCache();
        
        // Loop through sheets and select the one with 'Смета' in the name
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            if (strpos(strtolower($worksheet->getTitle()), 'смета') !== false || strpos(strtolower($worksheet->getTitle()), 'договор') !== false) {
                $smetaSheet = $worksheet;
                break;
            }
        }
        
        if (!isset($smetaSheet)) {
            throw new Exception('Sheet with "Смета" or "Договор" not found');
        }
        
        $parameters['sheet_structure'] = $this->generateSheetStructure($smetaSheet, $foundation);
        $foundation->parameters = $parameters;
        $foundation->save();
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

    private function sanitizeSectionTitle($title) {
        //remove any numbers, dots and spaces before first letter
        $title = preg_replace('/^[^a-zA-Zа-яА-Я]+/', '', $title);
        return $title;
    }

    private function generateSheetStructure($worksheet, $foundation): array
    {
        $foundationTitle = $this->checkForTitleException($foundation);

        $sheetStructure = [
            "title" => $foundationTitle,
            "sections" => [],
            "endOfLabour" => "",
            "lastLetter" => "",
            "otherCols" => [],
        ];

        $worksheetData = $worksheet->toArray();

        // Find 'Наименование работ' and set up otherCols
        $searchPhrase = 'Наименование работ';
        foreach (array_slice($worksheetData, 0, 12) as $rowIndex => $row) {
            if (($colIndex = array_search(strtolower($searchPhrase), array_map('strtolower', $row))) !== false) {
                $nextRow = $worksheetData[$rowIndex + 1];
                foreach ($nextRow as $colIndex => $value) {
                    switch ($value) {
                        case 1:
                            $sheetStructure["otherCols"]["labourNumber"] = $colIndex;
                        case 2:
                            $sheetStructure["otherCols"]["labourTitle"] = $colIndex;
                            break;
                        case 3:
                            $sheetStructure["otherCols"]["labourUnit"] = $colIndex;
                            break;
                        case 4:
                            $sheetStructure["otherCols"]["labourQuantity"] = $colIndex;
                            break;
                        case 5:
                            $sheetStructure["otherCols"]["labourPrice"] = $colIndex;
                            break;
                        case 6:
                            $sheetStructure["otherCols"]["labourTotal"] = $colIndex;
                            $sheetStructure["endOfLabour"] = $colIndex;
                            break;
                        case 7:
                            $sheetStructure["otherCols"]["materialTitle"] = $colIndex;
                            break;
                        case 8:
                            $sheetStructure["otherCols"]["materialUnit"] = $colIndex;
                            break;
                        case 9:
                            $sheetStructure["otherCols"]["materialQuantity"] = $colIndex;
                            break;
                        case 10:
                            $sheetStructure["otherCols"]["materialPrice"] = $colIndex;
                            break;
                        case 11:
                            $sheetStructure["otherCols"]["materialTotal"] = $colIndex;
                            $sheetStructure["lastLetter"] = $colIndex;
                            break 3;
                    }
                }
            }
        }
        
        if (count($sheetStructure["otherCols"]) < 11) {
            Log::warning("Incomplete column structure found");
            return $sheetStructure;
        }

        

        $sheetStructure['endOfLabour'] = [
            "col" => Coordinate::stringFromColumnIndex($sheetStructure['endOfLabour'] + 1),
            "colIndex" => $sheetStructure['endOfLabour']
        ];
        $sheetStructure['lastLetter'] = [
            "col" => Coordinate::stringFromColumnIndex($sheetStructure['lastLetter'] + 1),
            "colIndex" => $sheetStructure['lastLetter']
        ];
        $sheetStructure['additionalLinesExist'] = $this->checkForAdditionalRowsAfterTitle($foundation);
        $sheetStructure['hasTitleSwaps'] = $this->hasTitleSwaps($foundation);
        $sheetStructure['hasSectionSwaps'] = $this->hasSectionSwaps($foundation);
        if (isset($foundation->exceptions['dynamicSwaps'])) {
            $sheetStructure['hasDynamicSwaps'] = true;
            $sheetStructure['dynamicSwaps'] = $foundation->exceptions['dynamicSwaps'];
        } else $sheetStructure['hasDynamicSwaps'] = false;
        
        if ($sheetStructure['hasTitleSwaps']) {
            $sheetStructure['titleSwaps'] = $foundation->exceptions['title'];
        }
        if ($sheetStructure['hasSectionSwaps']) {
            $sheetStructure['sectionSwaps'] = $foundation->exceptions['sectionTitle'];
        }
        if ($sheetStructure['additionalLinesExist']) {
            $sheetStructure['additionalLines'] = $foundation->exceptions['additionalLines'];
        }

        $sectionCount = 0;
        
        foreach ($worksheetData as $rowIndex => $row) {
            if (!empty($row[0]) && empty($row[3]) && empty($row[6])) {
                if ($sectionCount === 0 && !is_numeric($row[0][0])) {
                    continue;
                } else {
                    $value = $this->sanitizeSectionTitle($row[0]);
                    $sheetStructure['sections'][++$sectionCount] = [
                        "value" => $value,
                        "colIndex" => 0,
                        "rowIndex" => $rowIndex
                    ];
                }
            }
        }
        
        $sectionCount = count($sheetStructure['sections']);
        foreach ($sheetStructure['sections'] as $section => $sectionData) {
            if ($section === $sectionCount) {
                $lastSection = true;
            } else $lastSection = false;
            $sheetStructure['sections'][$section]['lastSection'] = $lastSection;
            if ($sectionTitle = $this->checkForSectionTitleException($foundation, $section)) {
                $sectionData['value'] = $sectionTitle;
                $sheetStructure['sections'][$section]['sectionTitleAction'] = true;
            } else $sheetStructure['sections'][$section]['sectionTitleAction'] = false;
            $rowIndex = $sectionData['rowIndex'] + 2; // Start 1 row below the current start
            $endRow = false;
            
            $sheetStructure['sections'][$section]['labourItems'] = [];
            $sheetStructure['sections'][$section]['materialItems'] = [];
        
            while (!$endRow) {
                // Get the row data
                $rowVals = $worksheet->rangeToArray('A' . $rowIndex . ':' . $sheetStructure['lastLetter']['col'] . $rowIndex, null, true, false)[0];
                
                $isLastRow = $this->isLastRow($rowVals, $sheetStructure);
        
                if ($isLastRow) {
                    $endRow = true;
                    $sheetStructure['sections'][$section]['sectionTotal'] = $sheetStructure['endOfLabour']['col'] . $rowIndex;
                } else {
                    $this->addItemsToSection($sheetStructure, $section, $rowVals, $rowIndex);
                }
        
                $rowIndex++;
            }
        
            $sheetStructure['sections'][$section]['endRow'] = $rowIndex - 1;
        }

        foreach ($sheetStructure['sections'][$sectionCount]['materialItems'] as $materialIndex => $material) {
            $sheetStructure['sections'][$sectionCount]['materialItems'][$materialIndex]['materialTotalCell'] = "A1";
            $sheetStructure['sections'][$sectionCount]['materialItems'][$materialIndex]['materialPriceCell'] = "A1";
        }

        // Find total box
        $searchPhrases = ["start" => "Ст-ть работ", "end" => "ВСЕГО по смете"];

        // Get the end row of the last section
        $lastSectionEndRow = max(array_column($sheetStructure['sections'], 'endRow'));

        // Determine the range to search
        $startRow = min($lastSectionEndRow + 1, $worksheet->getHighestRow());
        $endRow = max(180, $startRow);

        for ($rowIndex = $startRow; $rowIndex <= $endRow; $rowIndex++) {
            $row = $worksheet->rangeToArray('A' . $rowIndex . ':' . $worksheet->getHighestColumn() . $rowIndex, null, true, false)[0];
            
            foreach ($row as $colIndex => $value) {
                if (is_string($value) && strpos(strtolower($value), strtolower($searchPhrases["start"])) !== false) {
                    // Find the first numerical value in the row
                    $numericColIndex = null;
                    for ($i = $colIndex + 1; $i < count($row); $i++) {
                        if (is_numeric($row[$i]) || (is_string($row[$i]) && preg_match('/^[\d\s,.]+$/', $row[$i]))) {
                            $numericColIndex = $i;
                            break;
                        }
                    }
                
                    if ($numericColIndex !== null) {
                        $sheetStructure['boxStart'] = [
                            "rowIndex" => $rowIndex,
                            "col" => Coordinate::stringFromColumnIndex($numericColIndex + 1),
                            "firstCell" => Coordinate::stringFromColumnIndex($numericColIndex + 1) . $rowIndex
                        ];
                    }
                    break 2; // Exit both loops
                }
            }
        }

        if (!isset($sheetStructure['boxStart'])) {
            Log::warning("boxStart not found in the specified range");
        }

        return $sheetStructure;
    }

    private function isLastRow($rowVals, $sheetStructure)
    {
        $labourTotal = $rowVals[$sheetStructure['otherCols']['labourTotal']];
        $materialTotal = $rowVals[$sheetStructure['otherCols']['materialTotal']];
        
        // Check if both labour and material totals are empty
        if (empty($labourTotal) && empty($materialTotal)) {
            return false;
        }
        
        // Check if the row contains a total (usually indicated by a non-empty total and empty title)
        $labourTitle = $rowVals[$sheetStructure['otherCols']['labourTitle']];
        $materialTitle = $rowVals[$sheetStructure['otherCols']['materialTitle']];
        
        if (((!empty($labourTotal) && empty($labourTitle)) || (!empty($materialTotal) && empty($materialTitle)))) {
            return true;
        }
        
        return false;
    }

    private function checkForAdditionalTitle($title, string $colCheck, $spareCol): bool
    {
        if (!is_null($title) && !is_null($spareCol)) {
            if ($colCheck === 'G' || $colCheck === 'M') {
                if ($title !== $spareCol) {
                    return true;
                }
            }
        }
        return false;
    }

    private function checkForFormatException($title, $format, $decimalPlaces)
    {
        return [$format, $decimalPlaces];
    }

    private function hasTitleSwaps($foundation) {
        if (isset($foundation->exceptions['title'])) {
            return true;
        }
        return false;
    }   

    private function hasSectionSwaps($foundation) {
        if (isset($foundation->exceptions['sectionTitle'])) {
            return true;
        }
        return false;
    }
    


    private function checkForSubHeading($title, $subHeadings)
    {
        if (is_null($title)) {
            return false;
        }
        $title = str_replace(' ', '', $title);
        foreach ($subHeadings as $key => $subHeading) {
            if (isset($subHeading['partial'])) {
                $partial = str_replace(' ', '', $subHeading['partial']);
                Log::info("checking if $title contains " . $partial);
                if (strpos($title, $partial) !== false) {
                    return [true, $key];
                }
            }
            if (isset($subHeading['full'])) {
                $full = str_replace(' ', '', $subHeading['full']);
                Log::info("checking if $title is same as " . $full);
                if ($full == $title) {
                    return [true, $key];
                }
            }
        }
        Log::info("returning false for title $title");
        return false;
    }

    private function addItemsToSection(array &$sheetStructure, int $section, array $rowVals, int $rowIndex): void
    {
        $labourTotalCol = $sheetStructure['endOfLabour']['col'];
        $labourTitle = $rowVals[$sheetStructure['otherCols']['labourTitle']] ?? null;
        $labourNumber = $rowVals[$sheetStructure['otherCols']['labourNumber']] ?? null;
        $labourUnit = $rowVals[$sheetStructure['otherCols']['labourUnit']] ?? null;

        $labourSpareCol = $rowVals[2];
        $labourAdditionalTitle = $this->checkForAdditionalTitle($labourUnit, $labourTotalCol, $labourSpareCol);
        $labourAdditionalTitleCell = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($labourTotalCol) - 4) . $rowIndex;
        $labourQuantityCol = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($labourTotalCol) - 2);
        if (isset($this->replacementArray[$labourUnit])) {
            $labourUnit = $this->replacementArray[$labourUnit];
        }
        $labourPriceCol = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($labourTotalCol) - 1);
        
        $materialTitle = $rowVals[$sheetStructure['otherCols']['materialTitle']] ?? null;
        $materialTotalCol = $sheetStructure['lastLetter']['col'];
        $materialQuantityCol = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($materialTotalCol) - 2);
        
        $materialPriceCol = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($materialTotalCol) - 1);
        $materialUnit = $rowVals[$sheetStructure['otherCols']['materialUnit']] ?? null;
        if (isset($this->replacementArray[$materialUnit])) {
            $materialUnit = $this->replacementArray[$materialUnit];
        }
        $materialSpareCol = $rowVals[$sheetStructure['otherCols']['materialUnit']-1];
        $materialAdditionalTitle = $this->checkForAdditionalTitle($materialUnit, $materialTotalCol, $materialSpareCol);
        $materialAdditionalTitleCell = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($materialTotalCol) - 4) . $rowIndex;
        $decimalPlaces = $this->decimalPlaces;


        $labourDecimalPlaces = $this->getDecimalPlaces('labour', $labourUnit);
        $materialDecimalPlaces = $this->getDecimalPlaces('material', $materialUnit);

        $labourFormat = $this->format[$labourDecimalPlaces];
        $materialFormat = $this->format[$materialDecimalPlaces];

        $labourFormatException = $this->checkForFormatException($labourTitle, $labourFormat, $labourDecimalPlaces);
        $materialFormatException = $this->checkForFormatException($materialTitle, $materialFormat, $materialDecimalPlaces);
        $labourFormat = $labourFormatException[0];
        $labourDecimalPlaces = $labourFormatException[1];
        $materialFormat = $materialFormatException[0];
        $materialDecimalPlaces = $materialFormatException[1];

        
        $labourSubHeading = false;
        /* No current labour subheadings
        $labourSubHeading = $this->checkForSubHeadingAndDecimalExceptions($labourTitle, $labourDecimalPlaces);
        if ($labourSubHeading) {    
            $labourSubHeadingKey = $labourSubHeading[1];
            $labourSubHeading = $labourSubHeading[0];
        } */
        $materialSubHeading = $this->checkForSubHeading($materialTitle, $this->subHeadings);
        if ($materialSubHeading) {
            $materialSubHeadingKey = $materialSubHeading[1];
            $materialSubHeading = $materialSubHeading[0];
        }
        
        $labourItem = [
            "labourNumber" => $labourNumber,
            "labourTitle" => $labourTitle,
            "labourUnit" => $labourUnit,
            "labourPriceCell" => $labourPriceCol . $rowIndex,
            "labourQuantityCell" => $labourQuantityCol . $rowIndex,
            "labourTotalCell" => $labourTotalCol . $rowIndex,
            "labourDecimalPlaces" => $labourDecimalPlaces,
            "labourFormat" => $labourFormat,
            "labourAdditional" => $labourAdditionalTitle,
            "labourSubHeading" => $labourSubHeading,
            "labourAdditionalTitleCell" => $labourAdditionalTitleCell ?? null
        ];

        $materialItem = [
            "materialTitle" => $materialTitle,
            "materialUnit" => $materialUnit,
            "materialPriceCell" => $materialPriceCol . $rowIndex,
            "materialQuantityCell" => $materialQuantityCol . $rowIndex,
            "materialTotalCell" => $materialTotalCol . $rowIndex,
            "materialDecimalPlaces" => $materialDecimalPlaces,
            "materialFormat" => $materialFormat,
            "materialAdditional" => $materialAdditionalTitle,
            "materialSubHeading" => $materialSubHeading,
            "materialAdditionalTitleCell" => $materialAdditionalTitleCell ?? null
        ];

        if ($materialSubHeading) {
            $materialItem['materialSubheadingParams'] = $this->subHeadingParams[$materialSubHeadingKey];
        }

        if ($labourAdditionalTitle) {
            $labourItem['labourAdditionalTitle'] = $labourSpareCol;
        }

        if ($materialAdditionalTitle) {
            $materialItem['materialAdditionalTitle'] = $materialSpareCol;
        }

        if (!empty(array_filter($labourItem))) {
            $sheetStructure['sections'][$section]['labourItems'][] = $labourItem;
        }

        if (!empty(array_filter($materialItem))) {
            $sheetStructure['sections'][$section]['materialItems'][] = $materialItem;
        }
    }

    private function getDecimalPlaces($type, $unit)
    {
        foreach ($this->decimalPlaces[$type] as $decimalPlace => $units) {
            if (in_array($unit, $units)) {
                return $decimalPlace;
            }
        }
        return 2; // Default to 2 decimal places if not found
    }
}
