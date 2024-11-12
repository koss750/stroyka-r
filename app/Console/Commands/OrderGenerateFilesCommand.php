<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateOrderExcelJob;
use App\Jobs\FoundationOrderPdfJob;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class OrderGenerateFilesCommand extends Command
{
    protected $signature = 'order:generate {projectId}';
    protected $description = 'Generate order files (Excel and PDF) for a given project';

    public function handle()
    {
        $projectId = $this->argument('projectId');

        $this->info("Dispatching jobs to generate order files for Project ID: $projectId");

        // Dispatch Excel job
        GenerateOrderExcelJob::dispatch($projectId);
        sleep(4);
        // Dispatch PDF job
        //FoundationOrderPdfJob::dispatch($projectId);

        $this->info('Jobs dispatched successfully. The order files will be generated in the background.');

        // Wait for jobs to complete (you might want to implement a more robust solution for production)
        sleep(5);

        $project = Project::find($projectId);
        $excelLink = $project->filepath ? $project->filepath : null;
        //$pdfLink = $project->pdf_filepath ? Storage::url($project->pdf_filepath) : null;

        $this->info("Excel file link: " . ($excelLink ?? 'Not generated'));
        //$this->info("PDF file link: " . ($pdfLink ?? 'Not generated'));
    }
}