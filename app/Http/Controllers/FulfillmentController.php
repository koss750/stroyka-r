<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Http\Request;
use App\Models\Design;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Jobs\ReindexProjectsJob;
use App\Models\InvoiceType;
use App\Services\SpreadsheetService;

class FulfillmentController extends Controller
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    public function processLatestProjects($projectCount)
    {
        // Set execution time limit to 90 minutes
        ini_set('max_execution_time', 5400);
        // set memory to 2g
        ini_set('memory_limit', '2048M');
        
        ReindexProjectsJob::dispatch($projectCount);
        return response()->json(['message' => 'Reindexing job dispatched'], 200);
    }

    public function createSmeta($design, $config)
    {
        $filePath = storage_path("app/templates/clean_Главный.xlsx");
        $design = Design::find($design);
        return $this->spreadsheetService->handle($filePath, $design, true, false, 1, $config);
    }

    public function process(Request $request)
    {
        if ($request->has('debug') && $request->debug > 0) {
            $debug = $request->debug;
        } else {
            $debug = 1;
        }
        // Check if debugging is enabled
        

        $designId = $request->design;
        $variant = $request->variant;
        $design = Design::where('id', $designId)->firstOrFail();
        $tapeWidth = $variant[4];
        if ($tapeWidth == 1) {
            $tapeWidth = 10;
        }
        $tapeWidth = $tapeWidth / 10;
        $tapeLength = $tapeWidth - 0.1;

        $testVals = [
            // Define your test values here
        ];

        $filename = $request->filename;
        $filePath = storage_path("app/templates/" . $filename);
        if (!file_exists($filePath)) {
            throw new \Exception("File does not exist.");
        }
        $sheetname = $request->sheetname;
        $cellData = $request->cellData ?? $testVals;
        if ($sheetname == 'all') {
            $filePath = $this->spreadsheetService->handle($filePath, $design, false);
            return response()->download($filePath);
        } else {
            $invoiceType = InvoiceType::where('label', $request->sheetname)->first();
            $filePath = $this->spreadsheetService->handle($filePath, $design, false, true, 1, $invoiceType);
            return response()->download($filePath);
        }
    }

    public function foundationFullFile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'foundation_type' => 'required|string',
            'excel_data' => 'required|array',
        ]);

        $template = Template::where('category', $request->foundation_type)->first();
        $filePath = storage_path("app/templates/" . $template->file);
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $excelData = $request->input('excel_data');

        // Define the path to the Excel template based on the foundation type
        $templatePath = storage_path("app/excel_templates/{$foundationType}_template.xlsx");

        // Load the Excel file
        $spreadsheet = IOFactory::load($templatePath);

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Update cell values
        foreach ($excelData as $cell => $value) {
            $worksheet->setCellValue($cell, $value);
        }

        // Create a new Xlsx writer
        $writer = new Xlsx($spreadsheet);

        // Set the content type
        $fileName = "{$foundationType}_calculation_" . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');

        exit;
    }
}