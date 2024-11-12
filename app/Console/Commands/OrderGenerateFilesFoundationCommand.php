<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Foundation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FoundationOrderFileJob;

class OrderGenerateFilesFoundationCommand extends Command
{
    protected $signature = 'order:generate-f {projectId}';
    protected $description = 'Generate order files (Excel) for a given foundation project';

    protected $spreadsheet;
    protected $cellMappings;

    public function handle()
    {
        $projectId = $this->argument('projectId');
        $project = Project::findOrFail($projectId);
        $foundation = $project->foundation;

        if (!$foundation) {
            $this->error("No foundation found for Project ID: $projectId");
            return 1;
        }

        dispatch(new FoundationOrderFileJob($project));
    }

    protected function loadSpreadsheet($foundation)
    {
        $templatePath = storage_path('templates/foundation/' . $foundation->template_path . '.xlsx');
        $this->spreadsheet = IOFactory::load($templatePath);
    }

    protected function populateSpreadsheet($foundation)
    {
        $sheet = $this->spreadsheet->getSheetByName("data");

        foreach ($this->cellMappings as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        Calculation::getInstance($this->spreadsheet)->clearCalculationCache();
    }

    protected function processFoundation($foundation)
    {
        $smetaSheet = $this->findSmetaSheet();
        if (!$smetaSheet) {
            throw new \Exception('Sheet with "Смета" not found');
        }

        $sheetStructure = $this->generateSheetStructure($smetaSheet, $foundation->title);
        dd($sheetStructure);
        $foundation->parameters['sheet_structure'] = $sheetStructure;
        $foundation->save();
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

    protected function generateSheetStructure($sheet, $foundationTitle)
    {
        

        // Implement the logic to populate the sheet structure
        // This will be similar to the generateSheetStructure method in FullIndexCommand
        // You'll need to adapt it for the foundation-specific structure

        return $sheetStructure;
    }

    protected function saveSpreadsheet($project)
    {
        $writer = new Xlsx($this->spreadsheet);
        $filename = "project_{$project->id}_foundation_order_" . date('Y-m-d_H-i-s') . '.xlsx';
        $path = 'projects/' . $filename;
        
        Storage::put($path, '');
        $writer->save(Storage::path($path));

        $project->filepath = $path;
        $project->save();
    }
}