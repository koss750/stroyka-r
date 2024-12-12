<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use App\Models\Design;
use Illuminate\Support\Collection;
use App\Models\InvoiceType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\ProjectPrice;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;



class IndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:full {--range= : Range of design IDs (e.g., 1-100)}
                            {--id= : Single design ID}
                            {--defaultOnly : Only update default reference}';

    protected $description = 'Index all the things with optional range or single design';

    protected $combinedSheetRows = [];
    protected $additionalDeletedRows = 0;
    protected $invoices;
    protected $totalBoxes = [0, 0, 0, 0, 0, 0, 0];

    protected $cellMappings = [
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
            'D377' => 'mvPart12',
            'D378' => 'mvPart11',
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
    protected $fVariationArray = [
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
    protected $plitaVariationArray = [
        ['0.2', 'fMono20'],
        ['0.25', 'fMono25'],
        ['0.3', 'fMono30'],
        ['0.35', 'fMono35'],
    ];
    protected $metalAndPlasticVariationArray = [
        ['Смета Мягкая', 'rSoftP', 'I100', 'J100'],
        ['Смета Мягкая', 'rSoftM', 'I101', 'J101'],
        ['Смета Мягкая', 'rSoftN', 'I99', 'J99'],
        ['Смета Железо', 'rMetalP', 'I119', 'J119'],
        ['Смета Железо', 'rMetalM', 'I120', 'J120'],
        ['Смета Железо', 'rMetalN', 'I118', 'J118'],
    ];
    protected $exceptionalSheetsArray = [
        'Смета СВ-Рост 600х300', 'Смета плита', 'Смета лента 600х300'
    ];
    protected $exceptionalSheetsArrayPostOP = [148, 171, 172];
    protected $zhelezoVariationArray = [
            'section' => 3,
            'materialTitle' => 'Металлочерепица Grand Line серия Classic 0,5',
            'specialStyle' => true,
            'materialTitleFont' => 'bold',
            'additionalRowStart' => 2,
            'additionalRowUnit' => 'дл./шт.'
    ];
    protected $designs;
    protected $spreadsheet;

    protected $hardcodedIds = [
        "emptyCases" => [312, 173],
        "plitaCases" => [148, 171, 172]
    ];

    protected $hardcodedParams = [
        "emptyCases" => [
            "material" => 0,
            "labour" => 0,
            "total" => 0
        ]
    ];

    public function handle()
    {

        $range = $this->option('range');
        $singleId = $this->option('id');
        $defaultRefOnly = $this->option('defaultOnly');

        
        if ($range) {
            list($start, $end) = explode('-', $range);
            $this->designs = Design::whereBetween('id', [$start, $end])->where('active', '1')->get();
        } elseif ($singleId) {
            $this->designs = Design::where('id', $singleId)->where('active', '1')->whereNotIn('id', $this->hardcodedIds['plitaCases'])->get();
        } else {
            $this->designs = Design::where('active', '1')->whereNotIn('id', $this->hardcodedIds['plitaCases'])->get();
        }
        
        $this->invoices = InvoiceType::where('sheetname', '!=', null)->where('site_level4_label', "!=", "FALSE")->get();
        $sheetnames = $this->invoices->pluck('sheetname')->unique()->toArray();
        
        
        //$this->spreadsheet = IOFactory::createReader('Xlsx')->load(storage_path('templates/test.xlsx'));
        
        $this->info("Adding empty cases");
        $bar = $this->output->createProgressBar(count($this->designs));
        //change it later to handle from file
        
        $bar->start();
        foreach ($this->designs as $design) {
            $emptyCases = [312, 173];
            foreach ($emptyCases as $emptyCase) {   
                $NewprojectPrice = new ProjectPrice();
                $NewprojectPrice->design_id = $design->id;
                $NewprojectPrice->invoice_type_id = $emptyCase;
                $NewprojectPrice->price = json_encode(["material" => 0, "labour" => 0, "total" => 0]);
                $NewprojectPrice->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("Moving on...");
        

        $progressBar = $this->output->createProgressBar(count($this->designs));
        
        $progressBar->start();

        $results = [];
        foreach ($this->designs as $design) {
            
            //$this->resetSpreadsheetVariables();
            $results[$design->id] = [];
            $results[$design->id][0] = [];

            
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            
            
            $details = json_decode($design->details, true);
            $defaultRef = $details['defaultRef'];

            $this->processDataAndBalki($design);
            foreach ($sheetnames as $sheetname) {
                
                $invoice = $this->invoices->where('sheetname', $sheetname)->first();
                $cacheKey = "design_results_{$design->id}_{$invoice->id}";
                $cachedResults = Cache::get($cacheKey);
    
                if ($cachedResults && Carbon::parse($cachedResults['timestamp'])->diffInHours(now()) < 48) {
                    $this->info("Using cached results for Design {$design->id} and invoice {$invoice->id}");
                    $results[$design->id][$invoice->id] = $cachedResults['data'];
                } else {
                    if ($defaultRefOnly && $invoice->ref != $defaultRef) {
                        continue;
                    }

                    // Empty cases
                    if ($invoice->id == 220 or $invoice->id == 222) {
                        $results[$design->id][$invoice->id]['price'] = json_encode([
                            'material' => 0,
                            'labour' => 0,
                            'total' => 0
                        ]);
                        continue;
                    }

                    // Pre OP special cases
                    if (in_array($sheetname, $this->exceptionalSheetsArray)) {
                        $specialArray = $this->handleExceptionalSheet($sheetname, $design);
                        if ($specialArray != null) {
                            foreach ($specialArray as $invoiceId => $result) {
                                $results[$design->id][$invoiceId]['price'] = $result['price'];
                                $costs = json_decode($result['price'], true);
                                $parameters = json_decode($result['parameters'], true);
                                $parameters["sheet_structure"]["totals"] = [
                                    "material" => $costs['material'],
                                    "labour" => $costs['labour'],
                                    "total" => $costs['total'],
                                    "shipping" => $this->getShippingCost($parameters["sheet_structure"]["sections"])
                                ];
                                $results[$design->id][$invoiceId]['parameters'] = json_encode($parameters);
                                
                            }
                        }
                    } else {
                        $parameters = $this->getSheetParameters($sheetname, $design);
                        $costs = $this->processSheet($sheetname, $design);
                        $parameters["sheet_structure"]["totals"] = [
                            "material" => $costs["material"],
                            "labour" => $costs["labour"],
                            "total" => $costs["total"],
                            "shipping" => $this->getShippingCost($parameters["sheet_structure"]["sections"])
                        ];
                        
                        $results[$design->id][$invoice->id]['price'] = json_encode($costs);
                        $results[$design->id][$invoice->id]['parameters'] = json_encode($parameters) ?? json_encode([error => 'error']);

                    }


                    // Post OP special cases
                    if (in_array($invoice->id, $this->exceptionalSheetsArrayPostOP)) {
                        $parameters = json_decode($results[$design->id][$invoice->id]['parameters'], true);
                        $parameters = $this->handleZhelezo($parameters);

                        Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
                        $results[$design->id][$invoice->id]['parameters'] = json_encode($parameters);
                    }

                    // add to cache
                    try {
                        $cacheData = [
                            'data' => $results[$design->id][$invoice->id],
                            'timestamp' => now()->toDateTimeString()
                        ];
                        
                        Cache::put($cacheKey, $cacheData, now()->addHours(48));
                    } catch (\Exception $e) {
                        if ($invoice->id != 209) {
                            Log::error("Failed to cache results for design {$design->id}, invoice {$invoice->id}: " . $e->getMessage());
                        }
                    }

                    if ($defaultRefOnly && $invoice->ref == $defaultRef) {
                        
                        $details['price'] = json_decode($results[$design->id][$invoice->id]['price'], true);
                        $design->details = json_encode($details);

                        $design->save();
                    }
                }
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info("\nUpdating database...");
        [$updatedCount, $createdCount, $paramsChangedCount] = $this->updateDB($results);
        $this->info("\nDone!");
        $this->info("\nRecords updated: " . $updatedCount);
        $this->info("\nRecords created: " . $createdCount);
        $this->info("\nParameters changed: " . $paramsChangedCount);

        // Add this block to save the spreadsheet copy for a single ID
        if ($singleId) {
            $materialPrice = 0;
            foreach ($results[$singleId] as $invoiceId => $result) {
                if ($invoiceId != 0) {
                    $decodedResult = json_decode($result['price'], true);
                    if (isset($decodedResult['material'])) {
                        $materialPrice += $decodedResult['material'];
                    }
                }
            }
            $this->info("\nMaterial price: " . number_format($materialPrice, 2));
            $this->saveSpreadsheetCopy($singleId);
        }

        //$this->fixProjectPrices();
    }

    protected function getShippingCost($sections) {
        $lastSection = $sections[count($sections)];
        return $lastSection['sectionTotalMaterial'];
    }

    protected function updateDB($results)
    {
        $updatedCount = 0;
        $createdCount = 0;
        $paramsChangedCount = 0;
        $defaultRefOnly = $this->option('defaultOnly');
        $progressBar = $this->output->createProgressBar(count($results));
        $progressBar->start();
        foreach ($results as $designId => $invoices) {
            foreach ($invoices as $invoiceId => $result) {
                if ($invoiceId == 0) {
                    continue;
                }
                $existingPP = ProjectPrice::where('design_id', $designId)->where('invoice_type_id', $invoiceId)->first();
                $price = $result['price'];
                $parameters = $result['parameters'];
                if ($existingPP) {
                    if ($existingPP->price != $price) {
                        $updatedCount++;
                        $existingPP->price = $price;
                    }
                    if (base64_encode($existingPP->parameters) != base64_encode($parameters)) {
                        $paramsChangedCount++;
                        $existingPP->parameters = $parameters;
                    }
                    $existingPP->save();
                    
                } else {
                    $projectPrice = new ProjectPrice();
                    $projectPrice->design_id = $designId;
                    $projectPrice->invoice_type_id = $invoiceId;
                    $projectPrice->updated_at = now();
                    $projectPrice->price = $price;
                    $projectPrice->parameters = $parameters;
                    $projectPrice->save();
                    $createdCount++;
                }
            }

            if ($defaultRefOnly) {
                $design = Design::find($designId);
                $details = json_decode($design->details, true);
                $defaultRef = $details['defaultRef'];
                $invoice = $this->invoices->where('ref', $defaultRef)->first();
                $price = json_decode($results[$designId][$invoice->id]["price"], true);
                $details['price'] = $price;
                $design->details = json_encode($details);
                $design->save();
            }
            $progressBar->advance();
        }
        $progressBar->finish();

        return [$updatedCount, $createdCount, $paramsChangedCount];
    }

    protected function processSheet($sheetname, $design)
    {
        $sheet = $this->spreadsheet->getSheetByName($sheetname);
        $invoice = $this->invoices->where('sheetname', $sheetname)->first();
        $params = json_decode($invoice->params, true);
        $sheetStructure = $params['sheet_structure'];

        $materialTotal = 0;
        $labourTotal = 0;

        // Log material totals for each section
        foreach ($sheetStructure['sections'] as $sectionKey => $section) {
            foreach ($section['materialItems'] as $materialItem) {
                $materialCell = $materialItem['materialTotalCell'];
                $materialValue = $this->getCellValue($sheet, $materialCell);
                $materialTotal += $materialValue;

                // Log the material total for each line item
                $itemName = $materialItem['name'] ?? "Unknown Item in {$sectionKey}";
            }
        }

        $boxStart = $sheetStructure['boxStart'];
        $materialCell = $this->getMaterialCell($boxStart);
        $labourCell = $this->getLabourCell($boxStart);
        $totalCellValue = $this->getTotalValue($sheet, $boxStart);

        $testVar = $sheet->getCell($materialCell)->getCalculatedValue();
        if (is_numeric($testVar)) {
            $materialCellValue = round($testVar, 2);
            $labourCellValue = round($sheet->getCell($labourCell)->getCalculatedValue(), 2);
            $total = $totalCellValue;
        } else {
            $materialCellValue = 999;
            $labourCellValue = 999;
            $total = 999;
        }
        return [
            'material' => $materialCellValue,
            'labour' => $labourCellValue,
            'total' => $total
        ];
    }

    protected function getSheetParameters($sheetname, $design)
{
    $sheet = $this->spreadsheet->getSheetByName($sheetname);
    $invoice = $this->invoices->where('sheetname', $sheetname)->first();
    $params = json_decode($invoice->params, true); // Decode as array
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

    foreach ($unsetArray as $sectionKey) {
        unset($sheetStructure['sections'][$sectionKey]);
    }

    // Add boxStart values
    if (isset($sheetStructure['boxStart'])) {
        $sheetStructure['boxStart']['value'] = $this->getCellValue($sheet, $sheetStructure['boxStart']['firstCell']);
    }

    // Update the sheet_structure in the params array
    $params['sheet_structure'] = $sheetStructure;

    return $params;
    }

    protected function getBoxValues($boxStart) {
        $materialCell = $this->getMaterialCell($boxStart);
        $labourCell = $this->getLabourCell($boxStart);
        $totalCellValue = $this->getTotalValue($sheet, $boxStart);
    }

    protected function getCellValue($sheet, $cellReference)
    {
        $cellValue = $sheet->getCell($cellReference)->getCalculatedValue();
        return is_numeric($cellValue) ? floatval($cellValue) : $cellValue;
    }
    

    protected function processDataAndBalki($design)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
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

        // Кровля мягкая
        foreach ($this->cellMappings['rSoft'] as $cell => $value) {
            if (is_numeric($design->$value)) {
                $sheet->setCellValue($cell, $design->$value);
            } else {
                $sheet->setCellValue($cell, 0);
            }
        }

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
        $sheet = $this->spreadsheet->getSheetByName('балки');
        $startingIndex = 15;
        $endingIndex = 80;
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
    }

    protected function getMaterialCell($boxStart) {
        if (is_array($boxStart)) {
            return ($boxStart['col']) . ($boxStart['rowIndex'] + 4);
        } else {
            return ($boxStart->col) . ($boxStart->rowIndex + 4);
        }
    }

    protected function getLabourCell($boxStart) {
        if (is_array($boxStart)) {
            return ($boxStart['col']) . ($boxStart['rowIndex']);
        } else {
            return ($boxStart->col) . ($boxStart->rowIndex);
        }
    }

    protected function getTotalValue($sheet, $boxStart) {
        if (is_array($boxStart)) {
            $col = $boxStart['col'];
            $rowIndex = $boxStart['rowIndex'];
        } else {
            $col = $boxStart->col;
            $rowIndex = $boxStart->rowIndex;
        }
        $total = $sheet->getCell($col . ($rowIndex + 10))->getCalculatedValue();
        $deduction1 = $sheet->getCell($col . ($rowIndex + 9))->getCalculatedValue();
        $deduction2 = $sheet->getCell($col . ($rowIndex + 6))->getCalculatedValue();
        $total = $total - $deduction1 - $deduction2;
        return $total;
    }



    

    private function handleExceptionalSheet($exception, $design)
    {
        switch ($exception) {
            case "Смета СВ-Рост 600х300":
                return $this->handleSVRost($design);
                break;
            case "Смета плита":
                return $this->handlePlita($design);
                break;
            case "Смета лента 600х300":
                return $this->handleLenta($design);
                break;
            case "Железо":
        }
    }

    private function handleMyagkaya($design)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->metalAndPlasticVariationArray as $size) {
            $invoice = InvoiceType::where('label', $size[1])->first();
            $myagkayaSheet = $this->spreadsheet->getSheetByName($invoice->sheetname);
            $materialCell = $size[2];
            $labourCell = $size[3];
            $materialCellValue = $myagkayaSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $myagkayaSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoice->id]["price"] = json_encode($array);
            $result[$invoice->id]["parameters"] = json_encode($this->getSheetParameters($invoice->sheetname, $design));
        }
        return $result;
    }

    private function handleZhelezo($params)
    {
        $exceptionSection = $this->zhelezoVariationArray['section'];
        $originalItem = $params['sheet_structure']['sections'][$exceptionSection]['materialItems'][0];
        
        $newSectionItems = [];
        if ($originalItem['materialTitle'] == $this->zhelezoVariationArray['materialTitle']) {
            for ($i = 0; $i < 23; $i++) {
                unset($params['sheet_structure']['sections'][$exceptionSection]['materialItems'][$i]);
            }
            $originalItems = $params['sheet_structure']['sections'][$exceptionSection]['materialItems'];
            $newSectionItems[0] = $originalItem;
            $dataSheet = $this->spreadsheet->getSheetByName("data");
            $startRow = 281;
            $stop = false;
            $count = 1;
            while (!$stop) {
                $price = $dataSheet->getCell("M$startRow")->getCalculatedValue();
                $quantity = $dataSheet->getCell("L$startRow")->getCalculatedValue();
                if ($price == 0 && $quantity == 0) {
                    $stop = true;
                } else {
                    $newSectionItems[] = [
                        "materialAdditional" => false,
                        "materialTitle" => $count,
                        "materialUnit" => $this->zhelezoVariationArray['additionalRowUnit'],
                        "materialPrice" => $price,
                        "materialQuantity" => $quantity,
                        "materialTotal" => " "
                    ];
                    $count++;
                    $startRow++;
                }
            }
            $params['sheet_structure']['sections'][$exceptionSection]['materialItems'] = $newSectionItems;
            foreach ($originalItems as $item) {
                $params['sheet_structure']['sections'][$exceptionSection]['materialItems'][] = $item;
            }
            $labourItems = $params['sheet_structure']['sections'][$exceptionSection]['labourItems'];
            foreach ($labourItems as $index => $item) {
                if ($index >= count($params['sheet_structure']['sections'][$exceptionSection]['materialItems'])) {
                    unset($params['sheet_structure']['sections'][$exceptionSection]['labourItems'][$index]);
                }
            }
        }
        return $params;
    }

    private function handleSVRost($design)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        $boxStart = null;
        foreach ($this->fVariationArray as $size) {
            $invoiceSVR = InvoiceType::where('label', $size[3])->first();
            $svrSheet = $this->spreadsheet->getSheetByName($invoiceSVR->sheetname);
            $sheet->setCellValue('D5', $size[2]);
            $sheet->setCellValue('D8', $size[1]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $boxStart = json_decode($invoiceSVR->params);
            try {
                $boxStart = $boxStart->sheet_structure->boxStart;
            } catch (\Exception $e) {
                $boxStart = $boxStart["sheet_structure"]["boxStart"];
            }
            if ($boxStart == null) {
                json_decode($invoiceSVR->params);
            }
            $materialCell = $this->getMaterialCell($boxStart);
            $labourCell = $this->getLabourCell($boxStart);
            $materialCellValue = $svrSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $svrSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoiceSVR->id]["price"] = json_encode($array);
            $result[$invoiceSVR->id]["parameters"] = json_encode($this->getSheetParameters($invoiceSVR->sheetname, $design));
        }
        return $result;
    }

    private function handlePlita($design)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->plitaVariationArray as $size) {
            $invoicePlita = InvoiceType::where('label', $size[1])->first();
            $plitaSheet = $this->spreadsheet->getSheetByName($invoicePlita->sheetname);
            $sheet->setCellValue('D87', $size[0]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $params = json_decode($invoicePlita->params);
            $boxStart = $params->sheet_structure->boxStart;
            $materialCell = $this->getMaterialCell($boxStart);
            $labourCell = $this->getLabourCell($boxStart);
            $materialCellValue = $plitaSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $plitaSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoicePlita->id]["price"] = json_encode($array);
            $result[$invoicePlita->id]["parameters"] = json_encode($this->getSheetParameters($invoicePlita->sheetname, $design));
        }
        return $result;
    }

    protected function resetSpreadsheetVariables()
    {
        $dataSheet = $this->spreadsheet->getSheetByName("data");
        $balkiSheet = $this->spreadsheet->getSheetByName("балки");

        // Reset variables in the data sheet
        foreach ($this->cellMappings as $section => $mappings) {
            foreach ($mappings as $cell => $value) {
                $dataSheet->setCellValue($cell, 0);
            }
        }

        $dataRanges = [
            'L281:M302'
        ];

        // Reset variables in the балки sheet
        $balkiRanges = [
            'E15:H40',  // Assuming this is the range for floor data
            'P15:P40'   // Assuming this is the range for unique lengths
        ];

        foreach ($dataRanges as $range) {
            foreach ($dataSheet->getCell($range) as $cell) {
                $cell->setValue(0);
            }
        }

        foreach ($balkiRanges as $range) {
            foreach ($balkiSheet->getCell($range) as $cell) {
                $cell->setValue(0);
            }
        }

        // Clear the UNIQUE formula result
        $balkiSheet->getCell('P15')->setValue(null);

        Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
    }

    private function handleLenta($design)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->fVariationArray as $size) {
            $invoiceLenta = InvoiceType::where('label', $size[4])->first();
            $lentaSheet = $this->spreadsheet->getSheetByName($invoiceLenta->sheetname);
            $sheet->setCellValue('D5', $size[2]);
            $sheet->setCellValue('D8', $size[1]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $boxStart = json_decode($invoiceLenta->params)->sheet_structure->boxStart;
            $materialCell = $this->getMaterialCell($boxStart);
            $labourCell = $this->getLabourCell($boxStart);
            $materialCellValue = $lentaSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $lentaSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoiceLenta->id]["price"] = json_encode($array);
            $result[$invoiceLenta->id]["parameters"] = json_encode($this->getSheetParameters($invoiceLenta->sheetname, $design));
        }
        return $result;
    }

    protected function getCachedResult($designId)
    {
        $cacheKey = 'design_result_' . $designId;
        return Cache::get($cacheKey);
    }

    protected function cacheResult($designId, $result)
    {
        $cacheKey = 'design_result_' . $designId;
        // Cache for 1 day (24 hours * 60 minutes * 60 seconds)
        Cache::put($cacheKey, $result, 24 * 60 * 60);
    }

    protected function saveSpreadsheetCopy($designId)
    {
        $writer = new Xlsx($this->spreadsheet);
        $filename = "design_{$designId}_spreadsheet_" . date('Y-m-d_H-i-s') . '.xlsx';
        $path = storage_path('templates/processedTemplates/' . $filename);
        
        // Ensure the directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer->save($path);
        $this->info("\nSpreadsheet copy saved: " . $path);
    }

    public function fixProjectPrices() {
        $projectPrices = ProjectPrice::all();
        $bar = $this->output->createProgressBar(count($projectPrices));

        $this->info("Analyzing project prices...");
        $bar->start();

        $changedCount = 0;
        $unchangedCount = 0;
        $errorCount = 0;

        foreach ($projectPrices as $projectPrice) {
            $originalPrice = $projectPrice->price;
            
            // Check if the price is a string
            if (!is_string($originalPrice)) {
                $this->error("\nNon-string price found for ID {$projectPrice->id}: " . var_export($originalPrice, true));
                $errorCount++;
                $bar->advance();
                continue;
            }

            // Try to decode the JSON string
            $decodedPrice = json_decode($originalPrice, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("\nInvalid JSON for ID {$projectPrice->id}: $originalPrice");
                $errorCount++;
                $bar->advance();
                continue;
            }

            // Check if $decodedPrice is an array
            if (!is_array($decodedPrice)) {
                // If it's not an array, it might be double-encoded
                $decodedPrice = json_decode($decodedPrice, true);
                if (!is_array($decodedPrice)) {
                    $this->error("\nUnexpected format for ID {$projectPrice->id}: " . var_export($decodedPrice, true));
                    $errorCount++;
                    $bar->advance();
                    continue;
                }
            }

            // Round the numeric values to 2 decimal places
            foreach (['material', 'labour', 'total'] as $key) {
                if (isset($decodedPrice[$key]) && is_numeric($decodedPrice[$key])) {
                    $decodedPrice[$key] = round($decodedPrice[$key], 2);
                }
            }
            
            // Re-encode as a JSON string without escaped slashes
            $fixedPrice = json_encode($decodedPrice, JSON_UNESCAPED_SLASHES);
            
            // Check if the price has changed
            if ($fixedPrice !== $originalPrice) {
                $projectPrice->price = $fixedPrice;
                $projectPrice->save();
                $changedCount++;
            } else {
                $unchangedCount++;
            }

            

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAnalysis complete!");
        $this->info("Changed records: $changedCount");
        $this->info("Unchanged records: $unchangedCount");
        $this->info("Errors encountered: $errorCount");

        $this->info("Adding empty cases");
        $designs = Design::all();
        $bar = $this->output->createProgressBar(count($designs));
        $bar->start();
        foreach ($designs as $design) {
            $emptyCases = [312, 173];
            if (in_array($design->id, $emptyCases)) {
                $NewprojectPrice = new ProjectPrice();
                $NewprojectPrice->project_id = $design->id;
                $NewprojectPrice->price = json_encode(["material" => 0, "labour" => 0, "total" => 0]);
                $NewprojectPrice->save();
            }
            $bar->advance();
        }
        $bar->finish();
    }

    private function formatNumber($number, $decimalPlaces)
    {
        return number_format((float)$number, $decimalPlaces, '.', '');
    }
}