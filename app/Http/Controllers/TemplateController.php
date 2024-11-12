<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\InvoiceType;
use App\Models\OrderFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\FormField;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Jobs\ProcessTemplateJob;

class TemplateController extends Controller
{
    protected $validPasscode = '123';

    /**
     * Store the template file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTemplate(Request $request)
{
    //set time limit to 1 hour
    set_time_limit(3600);
    $validatedData = $request->validate([
        'file' => 'required|file',
        'category' => 'required|string'
    ]);

    $path = $request->file('file')->store('templates');
    $name = $request->file('file')->getClientOriginalName();

    $template = Template::create([
        'name' => $name,
        'file_path' => $path,
        'category' => $validatedData['category']
    ]);
    
    ProcessTemplateJob::dispatch($template);

    return back()->with('success', 'New template uploaded successfully.');
}

    public function updateTemplate(Request $request, $id)
{
    try {
        $template = Template::findOrFail($id);
        if ($request->hasFile('file')) {
            // Delete old file if necessary and store the new file
            Storage::delete($template->file_path);
            $path = $request->file('file')->storeAs('templates', $request->input('name', 'default_filename') . '.xlsx'); 

            $template->update(['file_path' => $path, 'name' => $request->name]);
        }
        if ($template->category == 'main') {
            ProcessTemplateJob::dispatch($template, $request->all());
        }

        return back()->with('success', 'Template updated successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error updating template: ' . $e->getMessage());
    }
}
//foundations only
public function generateExcel(Request $request)
{
    $foundationType = $request->input('foundation_type');
    $excelData = $request->input('excel_data');

    // Find the template
    $template = Template::where('category', $foundationType)->first();

    if (!$template) {
        return response()->json(['error' => 'Template not found'], 404);
    }

    // Get the file path
    $filePath = storage_path('app/' . $template->file_path);

    try {
        // Load the template
        $spreadsheet = IOFactory::load($filePath);

        $worksheet = $spreadsheet->setActiveSheetIndex(0);

        // Update cells with new data
        foreach ($excelData as $cell => $value) {
            $worksheet->setCellValue($cell, $value);
        }

        // Create a temporary file to save the modified spreadsheet
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Generate a unique filename for the download
        $downloadFilename = 'generated_' . $foundationType . '_' . time() . '.xlsx';

        // Move the temporary file to a publicly accessible location
        Storage::disk('public')->put($downloadFilename, file_get_contents($tempFile));

        // Delete the temporary file
        unlink($tempFile);

        // Generate the download URL
        $downloadUrl = Storage::disk('public')->url($downloadFilename);

        return response()->json([
            'download_url' => $downloadUrl
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error generating Excel file: ' . $e->getMessage()], 500);
    }
}

public function generateFoundationSmeta($spreadsheet)
{
    // Create a new spreadsheet
    $newSpreadsheet = new Spreadsheet();
    $newWorksheet = $newSpreadsheet->getActiveSheet();

    // Get the third sheet (index 2) from the original spreadsheet
    $sourceWorksheet = $spreadsheet->getSheet(2);

    // Copy cells A1:M100
    for ($col = 'A'; $col <= 'M'; $col++) {
        // Copy column width
        $columnIndex = Coordinate::columnIndexFromString($col);
        $columnDimension = $sourceWorksheet->getColumnDimension($col);
        $newWorksheet->getColumnDimension($col)
            ->setWidth($columnDimension->getWidth())
            ->setAutoSize($columnDimension->getAutoSize());

        for ($row = 1; $row <= 100; $row++) {
            $cell = $col . $row;
            
            // Get the calculated value (not the formula)
            $cellValue = $sourceWorksheet->getCell($cell)->getCalculatedValue();
            
            // Set the value as a string to avoid any formula interpretation
            $newWorksheet->setCellValueExplicit($cell, $cellValue, DataType::TYPE_STRING);
            
            // Copy styles
            $newWorksheet->getStyle($cell)->applyFromArray(
                $sourceWorksheet->getStyle($cell)->exportArray()
            );

            // Copy row height
            if ($col === 'A') {
                $rowDimension = $sourceWorksheet->getRowDimension($row);
                $newWorksheet->getRowDimension($row)
                    ->setRowHeight($rowDimension->getRowHeight())
                    ->setZeroHeight($rowDimension->getZeroHeight());
            }
        }
    }

    // Copy merged cells
    foreach ($sourceWorksheet->getMergeCells() as $mergeCell) {
        $newWorksheet->mergeCells($mergeCell);
    }

    // Create a temporary file to save the new spreadsheet
    $tempFile = tempnam(sys_get_temp_dir(), 'smeta_');
    $writer = new Xlsx($newSpreadsheet);
    $writer->save($tempFile);

    // Generate a unique filename for the download
    $downloadFilename = 'foundation_smeta_' . time() . '.xlsx';

    // Move the temporary file to a publicly accessible location
    Storage::disk('public')->put($downloadFilename, file_get_contents($tempFile));

    // Delete the temporary file
    unlink($tempFile);

    // Generate the download URL
    $downloadUrl = Storage::disk('public')->url($downloadFilename);

    return $downloadUrl;
}

    /**
     * Retrieve the template file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getTemplate(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'template_name' => 'required|string',
                'passcode' => 'required|string',
            ]);

            // Verify passcode
            if ($validatedData['passcode'] !== $this->validPasscode) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Retrieve the template from the database
            $template = Template::where('name', $validatedData['template_name'])->first();

            if (!$template) {
                return response()->json(['error' => 'Template not found'], 404);
            }

            $path = $template->file_path;

            if (!Storage::exists($path)) {
                return response()->json(['error' => 'File not found on disk'], 404);
            }

            return response()->download(storage_path('app/' . $path));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve template', 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Retrieve each template safely, or return null if not found
        $formFields = FormField::all()->groupBy('form_type');
        $mainTemplate = Template::where('category', 'main')->first();
        $sr = Template::where('category', 'sr')->first();
        $srs = Template::where('category', 'srs')->first();
        $plita = Template::where('category', 'plita')->first();
        $fLenta = Template::where('category', 'flenta')->first();  // Match the case
        $pLenta = Template::where('category', 'plenta')->first();  // Match the case
        $templates = Template::all();
        $orderFiles = OrderFile::with('design')->latest()->take(5)->get();
    
        return view('templates.index', compact('mainTemplate',  'pLenta', 'fLenta', 'plita', 'sr', 'srs', 'orderFiles', 'templates', 'formFields'));
    }

    public function initialProcessing(Template $template)
    {
        $filename = $template->name . '.xlsx';
        Log::info("Processing cells");
        if (!$filename) {
            return response()->json(['error' => 'Filename is required'], Response::HTTP_BAD_REQUEST);
        }

        $filePath = storage_path('app/templates/' . $filename);
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist'], Response::HTTP_BAD_REQUEST);
        }
        Log::info("File exists");
        try {
            Log::info("Loading spreadsheet from file: $filePath");
            $spreadsheet = IOFactory::load($filePath);

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheetTitle = $worksheet->getTitle();
                Log::info("Processing worksheet: $sheetTitle");
                if (strpos($sheetTitle, 'Смета') !== false) {
                    $this->initialSheetProcessing($worksheet);
                }
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($filePath);            
            Log::info("File processed and saved to: $filePath");
            
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheetTitle = $worksheet->getTitle();
                Log::info("Processing worksheet: $sheetTitle");
                if (strpos($sheetTitle, 'Смета') !== false) {
                    $invoiceObject = InvoiceType::where('sheetname', $worksheet->getTitle())->first();
                    if ($invoiceObject) {
                        $this->secondarySheetProcessing($worksheet, $invoiceObject->sheet_spec);
                    }
                }
            }
            
            
            $newWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filePathClean = storage_path('app/templates/clean_' . $filename);
            $newWriter->save($filePathClean); 
            Log::info("File processed and saved to: $filePathClean");
            
            return response()->json(['message' => 'File processed successfully', 'path' => "/storage/templates/$filename"], Response::HTTP_OK);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            Log::error("Spreadsheet read error: " . $e->getMessage());
            return response()->json(['error' => 'Error reading the spreadsheet'], Response::HTTP_BAD_REQUEST);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            Log::error("Spreadsheet error: " . $e->getMessage());
            return response()->json(['error' => 'Spreadsheet processing error'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error("General error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function downloadTemplate($category)
    {
        $template = Template::where('category', $category)->first();
        return Storage::download($template->file_path);
    }

    public function experimentalMain()
    {
        // Your experimental logic here
        return response()->json(['message' => 'Experimental function executed']);
    }
}
