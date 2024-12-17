<?php

namespace App\Console\Commands;

use App\Models\Template;
use App\Models\InvoiceType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class GenerateInvoiceStructuresCommand extends Command
{
    protected $signature = 'app:structures {invoice_type_id? : The ID of the InvoiceType to process}';
    protected $description = 'Generate invoice structures for one or all InvoiceTypes';

    public $exceptionalInvoices = [148, 171, 172];

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

    public $formatExceptions = [
        'Монтаж межвенцового утеплителя' => 0
    ];

    public $subHeadings = [
        1 => ["partial" => "Grand Line серия Classic"],
        2 => ["full" => "Расходные материалы "]
    ];

    public $subHeadingParams = [
        1 => ["entireRow" => false],
        2 => ["entireRow" => true]
    ];

    public function handle()
    {
        $invoiceTypeId = $this->argument('invoice_type_id');

        $spreadsheet = IOFactory::createReader('Xlsx')->load(storage_path('templates/test.xlsx'));

        $query = InvoiceType::where('site_level4_label', '!=', 'FALSE')
            ->whereNotNull('sheetname');

        if ($invoiceTypeId) {
            $query->where('id', $invoiceTypeId);
        }

        $invoiceTypes = $query->get();

        foreach ($invoiceTypes as $invoiceType) {
            $this->processInvoiceType($invoiceType, $spreadsheet);
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        $this->info('Invoice structures generated successfully.');
    }

    private function processInvoiceType(InvoiceType $invoiceType, $spreadsheet)
    {
        $sheetname = $invoiceType->sheetname;
        $this->info("Processing invoice type: " . $invoiceType->id . " (Sheet: " . $sheetname . ")");
        
        $worksheet = $spreadsheet->getSheetByName($sheetname);
        if ($worksheet) {
            $cacheKey = 'sheet_structure_' . md5($sheetname, $invoiceType->id);
            $structure = Cache::remember($cacheKey, now()->addHours(24), function () use ($worksheet, $sheetname, $invoiceType) {
                $this->info("Generating new structure for sheet: " . $sheetname);
                return $this->generateSheetStructure($worksheet, $invoiceType->customer_order_title, $invoiceType->id);
            });

            $params = json_decode($invoiceType->params, true) ?: [];
            $params['sheet_structure'] = $structure;
            $invoiceType->params = json_encode($params);
            $invoiceType->save();

            $this->info("Updated structure for invoice type: " . $invoiceType->id);
        } else {
            $this->warn("Worksheet not found for invoice type: " . $invoiceType->id);
        }
    }

    private function generateSheetStructure($worksheet, $invoiceTypeTitle, $invoiceId): array
    {
        $sheetStructure = [
            "title" => "",
            "sections" => [],
            "endOfLabour" => "",
            "lastLetter" => "",
            "otherCols" => [],
        ];

        $worksheetData = $worksheet->toArray();

        // Find 'Наименование работ' and set up otherCols
        $searchPhrase = 'Наименование работ';
        foreach (array_slice($worksheetData, 0, 12) as $rowIndex => $row) {
            if ($colIndex = array_search(strtolower($searchPhrase), array_map('strtolower', $row)) !== false) {
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

        // Find 'Раздел' and sections
        $searchPhrase = 'Раздел';
        foreach ($worksheetData as $rowIndex => $row) {
            if (isset($row[0]) && strpos(strtolower($row[0]), strtolower($searchPhrase)) !== false) {
                $sheetStructure['title'] = [
                    "value" => $invoiceTypeTitle,
                    "colIndex" => 0,
                    "rowIndex" => $rowIndex
                ];
                break;
            }
        }

        $sectionCount = 0;
        foreach ($worksheetData as $rowIndex => $row) {
            if (!empty($row[0]) && empty($row[3]) && empty($row[6])) {
                if ($sectionCount === 0 && !is_numeric($row[0][0])) {
                    continue;
                }
                $sheetStructure['sections'][++$sectionCount] = [
                    "value" => $row[0],
                    "colIndex" => 0,
                    "rowIndex" => $rowIndex
                ];
            }
        }
        
        $sectionCount = count($sheetStructure['sections']);
        foreach ($sheetStructure['sections'] as $section => $sectionData) {
            $rowIndex = $sectionData['rowIndex'] + 2; // Start 1 row below the current start
            $endRow = false;
            $lastSection = false;
            if ($section === $sectionCount) {
                $lastSection = true;
            }
            $sheetStructure['sections'][$section]['lastSection'] = $lastSection;
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

            // Find total box
            $searchPhrases = ["start" => "Ст-ть работ", "end" => "ВСЕГО по смете"];

            // Get the end row of the last section
            $lastSectionEndRow = max(array_column($sheetStructure['sections'], 'endRow'));

            // Determine the range to search
            $startRow = min($lastSectionEndRow + 1, $worksheet->getHighestRow());
            $endRow = max(180, $startRow);
            $C5filled = $worksheet->getCell('C5')->getValue();
            $C5Valid = strlen($C5filled) > 2 && strlen($C5filled) < 5;
            if ($C5Valid) {
                // first character of C5
                $firstLetter = substr($C5filled, 0, 1);
                // remove first letter from C5
                $rowIndex = substr($C5filled, 1);
                $sheetStructure['boxStart'] = [
                    "rowIndex" => $rowIndex,
                    "col" => $firstLetter,
                    "firstCell" => $firstLetter . $rowIndex
                ];
            }
            for ($rowIndex = $startRow; $rowIndex <= $endRow; $rowIndex++) {
                
                if ($C5Valid) continue;
                Log::info("C5 is not valid for worksheet " . $worksheet->getTitle());

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
                            $cellValue = $worksheet->getCell(Coordinate::stringFromColumnIndex($numericColIndex + 1) . $rowIndex)->getCalculatedValue();
                            if ($cellValue == 0 || !is_numeric($cellValue)) {
                                $incrementedRowIndex = $rowIndex + 1;
                            } else {
                                $incrementedRowIndex = $rowIndex;
                            }
                            $sheetStructure['boxStart'] = [
                                "rowIndex" => $rowIndex,
                                "col" => Coordinate::stringFromColumnIndex($numericColIndex + 1),
                                "firstCell" => Coordinate::stringFromColumnIndex($numericColIndex + 1) . $incrementedRowIndex
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

    // Add these methods to your class
    private function isLastRow(array $rowVals, array $sheetStructure): bool
{
    $endOfLabourCol = $sheetStructure['endOfLabour']['colIndex'];
    $lastLetterCol = $sheetStructure['lastLetter']['colIndex'];

    return (is_null($rowVals[1]) && !is_null($rowVals[$endOfLabourCol])) ||
           (is_null($rowVals[$endOfLabourCol + 1]) && !is_null($rowVals[$lastLetterCol]) && !is_null($rowVals[$endOfLabourCol]));
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

    $labourDecimalPlaces = $this->getDecimalPlaces($labourUnit, $decimalPlaces['labour']);
    $materialDecimalPlaces = $this->getDecimalPlaces($materialUnit, $decimalPlaces['material']);

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

private function getDecimalPlaces($unit, $decimalPlacesArray)
{
    foreach ($decimalPlacesArray as $decimals => $units) {
        if (in_array($unit, $units)) {
            return $decimals;
        }
    }
    return 2; // Default to 2 decimal places if unit not found
}

private function checkForFormatException($title, $currentFormat, $currentDecimalPlaces) {
    foreach ($this->formatExceptions as $exception => $format) {
        if (strpos($title, $exception) !== false) {
            return [$this->format[$format], $format];
        }
    }
    return [$currentFormat, $currentDecimalPlaces];
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



    public function isFinished(): bool
    {
        return $this->finished;
    }
}