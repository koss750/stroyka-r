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



class IndexLightCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-l {--range= : Range of design IDs (e.g., 1-100)}
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
        'Смета СВ-Рост 600х300', 'Смета плита', 'Смета лента 600х300', 'Смета Железо', 'Смета Мягкая'
    ];
    protected $designs;
    protected $spreadsheet;

    public function handle()
    {

        $range = $this->option('range');
        $singleId = $this->option('id');
        $defaultRefOnly = $this->option('defaultOnly');

        $queryStartTime = microtime(true);
        if ($range) {
            list($start, $end) = explode('-', $range);
            $this->designs = Design::whereBetween('id', [$start, $end])->where('active', '1')->get();
        } elseif ($singleId) {
            $this->designs = Design::where('id', $singleId)->where('active', '1')->get();
        } else {
            $this->designs = Design::where('active', '1')->get();
        }
        $queryEndTime = microtime(true);
        

        $invoiceQueryStartTime = microtime(true);
        $this->invoices = InvoiceType::where('sheetname', '!=', null)->where('site_level4_label', "!=", "FALSE")->get();
        $sheetnames = $this->invoices->pluck('sheetname')->unique()->toArray();
        $invoiceQueryEndTime = microtime(true);
        

        $spreadsheetLoadStartTime = microtime(true);
        $this->spreadsheet = IOFactory::createReader('Xlsx')->load(storage_path('templates/test.xlsx'));
        
        $spreadsheetLoadEndTime = microtime(true);
        

        $progressBar = $this->output->createProgressBar(count($this->designs));
        $progressBar->start();

        $results = [];
        foreach ($this->designs as $design) {
            $designStartTime = microtime(true);
            
            //$this->resetSpreadsheetVariables();
            $results[$design->id] = [];
            $results[$design->id][0] = [];

            $clearCacheStartTime = microtime(true);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $clearCacheEndTime = microtime(true);
            
            $details = json_decode($design->details, true);
            $defaultRef = $details['defaultRef'];

            $balkiStartTime = microtime(true);
            $this->processDataAndBalki($design);
            $balkiEndTime = microtime(true);
            

            $sheetsTotalTime = 0;
            foreach ($sheetnames as $sheetname) {
                $sheetStartTime = microtime(true);
                
                $invoice = $this->invoices->where('sheetname', $sheetname)->first();
                

                if ($defaultRefOnly && $invoice->ref != $defaultRef) {
                    continue;
                }

                if ($invoice->id == 220 or $invoice->id == 222) {
                    $results[$design->id][$invoice->id] = json_encode([
                        'material' => 0,
                        'labour' => 0,
                        'total' => 0
                    ]);
                    continue;
                }
                if (in_array($sheetname, $this->exceptionalSheetsArray)) {
                    $specialArray = $this->handleExceptionalSheet($sheetname, $design);
                    if ($specialArray != null) {
                        foreach ($specialArray as $invoiceId => $result) {
                            $results[$design->id][$invoiceId] = $result;
                        }
                    }
                } else {
                    $results[$design->id][$invoice->id] = json_encode($this->processSheet($sheetname, $design));
                }
                if ($defaultRefOnly && $invoice->ref == $defaultRef) {
                    $details['price'] = json_decode($results[$design->id][$invoice->id], true);
                    $design->details = json_encode($details);
                    $design->save();
                    $this->info("\nDesign " . $design->id . " updated etiketka price");
                }
                $sheetEndTime = microtime(true);
                $sheetTime = $sheetEndTime - $sheetStartTime;
                $sheetsTotalTime += $sheetTime;
                
            }
            
            
            $designEndTime = microtime(true);
            $totalDesignTime = $designEndTime - $designStartTime;
            $unaccountedTime = $totalDesignTime - $sheetsTotalTime - ($balkiEndTime - $balkiStartTime) - ($clearCacheEndTime - $clearCacheStartTime);
            
            $progressBar->advance();
        }

        $progressBar->finish();

        $dbUpdateStartTime = microtime(true);
        $this->info("\nUpdating database...");
        [$updatedCount, $createdCount] = $this->updateDB($results);
        $dbUpdateEndTime = microtime(true);
        
        $totalEndTime = microtime(true);
        
        if ($singleId) {
            $materialPrice = 0;
            foreach ($results[$singleId] as $invoiceId => $result) {
                if ($invoiceId != 0) {
                    $decodedResult = json_decode($result, true);
                    if (isset($decodedResult['material'])) {
                        $materialPrice += $decodedResult['material'];
                    }
                }
            }
            $this->info("\nMaterial price: " . number_format($materialPrice, 2));
            $this->saveSpreadsheetCopy($singleId);
        }
        $this->info("\nDone!");
        $this->info("\nRecords updated: " . $updatedCount);
        $this->info("\nRecords created: " . $createdCount);
    }

    protected function updateDB($results)
    {
        $updatedCount = 0;
        $createdCount = 0;
        $defaultRefOnly = $this->option('defaultOnly');

        foreach ($results as $designId => $invoices) {
            foreach ($invoices as $invoiceId => $result) {
                if ($invoiceId == 0) {
                    continue;
                }
                $existingPP = ProjectPrice::where('design_id', $designId)->where('invoice_type_id', $invoiceId)->first();
                if ($existingPP) {
                    if ($existingPP->price !== json_encode($result)) {
                        $existingPP->price = json_encode($result);
                        $existingPP->save();
                        $updatedCount++;
                    }
                } else {
                    $projectPrice = new ProjectPrice();
                    $projectPrice->design_id = $designId;
                    $projectPrice->invoice_type_id = $invoiceId;
                    $projectPrice->updated_at = now();
                    $projectPrice->price = json_encode($result);
                    $projectPrice->save();
                    $createdCount++;
                }
            }

            if (!$defaultRefOnly) {
                $design = Design::find($designId);
                $details = json_decode($design->details, true);
                $defaultRef = $details['defaultRef'];
                $invoice = $this->invoices->where('ref', $defaultRef)->first();
                $price = json_decode($results[$designId][$invoice->id], true);
                $details['price'] = $price;
                $design->details = json_encode($details);
                $design->save();
            }
        }

        return [$updatedCount, $createdCount];
    }

    protected function processSheet($sheetname, $design)
    {
        $sheet = $this->spreadsheet->getSheetByName($sheetname);
        $invoice = $this->invoices->where('sheetname', $sheetname)->first();
        $materialCell = $this->getMaterialCell(json_decode($invoice->params));
        $labourCell = $this->getLabourCell(json_decode($invoice->params));
        $totalCellValue = $this->getTotalValue($sheet, json_decode($invoice->params));
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
        return ($boxStart->sheet_structure->boxStart->col) . ($boxStart->sheet_structure->boxStart->rowIndex + 5);
    }

    protected function getLabourCell($boxStart) {
        return ($boxStart->sheet_structure->boxStart->col) . ($boxStart->sheet_structure->boxStart->rowIndex + 1);
    }

    protected function getTotalValue($sheet, $boxStart) {
        $total = $sheet->getCell($boxStart->sheet_structure->boxStart->col . ($boxStart->sheet_structure->boxStart->rowIndex + 11))->getCalculatedValue();
        $deduction1 = $sheet->getCell($boxStart->sheet_structure->boxStart->col . ($boxStart->sheet_structure->boxStart->rowIndex + 10))->getCalculatedValue();
        $deduction2 = $sheet->getCell($boxStart->sheet_structure->boxStart->col . ($boxStart->sheet_structure->boxStart->rowIndex + 7))->getCalculatedValue();
        $total = $total - $deduction1 - $deduction2;
        return $total;
    }



    

    private function handleExceptionalSheet($exception, $design)
    {
        switch ($exception) {
            case "Смета СВ-Рост 600х300":
                return $this->handleSVRost();
                break;
            case "Смета плита":
                return $this->handlePlita();
                break;
            case "Смета лента 600х300":
                return $this->handleLenta();
                break;
            case "Смета Железо":
                break;
            case "Смета Мягкая":
                return $this->handleMyagkaya();
                break;
        }
    }

    private function handleMyagkaya()
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
            $result[$invoice->id] = json_encode($array);
        }
        return $result;
    }

    private function handleSVRost()
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->fVariationArray as $size) {
            $invoiceSVR = InvoiceType::where('label', $size[3])->first();
            $svrSheet = $this->spreadsheet->getSheetByName($invoiceSVR->sheetname);
            $sheet->setCellValue('D5', $size[2]);
            $sheet->setCellValue('D8', $size[1]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $materialCell = $this->getMaterialCell(json_decode($invoiceSVR->params));
            $labourCell = $this->getLabourCell(json_decode($invoiceSVR->params));
            $materialCellValue = $svrSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $svrSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoiceSVR->id] = json_encode($array);
        }
        return $result;
    }

    private function handlePlita()
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->plitaVariationArray as $size) {
            $invoicePlita = InvoiceType::where('label', $size[1])->first();
            $plitaSheet = $this->spreadsheet->getSheetByName($invoicePlita->sheetname);
            $sheet->setCellValue('D87', $size[0]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $materialCell = $this->getMaterialCell(json_decode($invoicePlita->params));
            $labourCell = $this->getLabourCell(json_decode($invoicePlita->params));
            $materialCellValue = $plitaSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $plitaSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoicePlita->id] = json_encode($array);
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

    private function handleLenta()
    {
        $sheet = $this->spreadsheet->getSheetByName("data");
        $result = [];
        foreach ($this->fVariationArray as $size) {
            $invoiceLenta = InvoiceType::where('label', $size[4])->first();
            $lentaSheet = $this->spreadsheet->getSheetByName($invoiceLenta->sheetname);
            $sheet->setCellValue('D5', $size[2]);
            $sheet->setCellValue('D8', $size[1]);
            Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
            $materialCell = $this->getMaterialCell(json_decode($invoiceLenta->params));
            $labourCell = $this->getLabourCell(json_decode($invoiceLenta->params));
            $materialCellValue = $lentaSheet->getCell($materialCell)->getCalculatedValue();
            $labourCellValue = $lentaSheet->getCell($labourCell)->getCalculatedValue();
            $total = $materialCellValue + $labourCellValue;
            $array = [
                'material' => $materialCellValue,
                'labour' => $labourCellValue,
                'total' => $total
            ];
            $result[$invoiceLenta->id] = json_encode($array);
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
}