<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Project;
use App\Models\InvoiceType;

class OrderComprehensiveCommand extends Command
{
    protected $signature = 'app:comp {projectId?}';
    protected $description = 'Comprehensive processing of a project or update all structures';

    public function handle()
    {
        $projectId = $this->argument('projectId');

        // Clear cache
        $this->info('Clearing cache...');
        Artisan::call('cache:clear');

        if ($projectId) {
            $this->processProject($projectId);
        } else {
            $this->updateAllStructures();
        }
    }

    protected function processProject($projectId)
    {
        $project = Project::findOrFail($projectId);
        $selectedConfig = $project->selected_configuration;

        $this->info("Processing Project ID: $projectId");

        // Get relevant InvoiceType IDs
        $invoiceTypeIds = $this->getInvoiceTypeIds($selectedConfig);
        $count = count($invoiceTypeIds);
        $progressBar = $this->output->createProgressBar($count + 2); // +2 for indexing and order generation
        $progressBar->start();

        // Generate structures for each InvoiceType
        foreach ($invoiceTypeIds as $invoiceTypeId) {
            $this->info("\nGenerating structure for InvoiceType ID: $invoiceTypeId");
            Artisan::call('app:structures', ['invoice_type_id' => $invoiceTypeId]);
            $progressBar->advance();
        }

        // Run full index for the design
        $designId = $project->design_id;
        $this->info("\nRunning full index for Design ID: $designId");
        Artisan::call('app:index', ['--id' => $designId]);
        $progressBar->advance();

        // Generate order file
        $this->info("\nGenerating order file for Project ID: $projectId");
        Artisan::call('order:generate', ['projectId' => $projectId]);
        $progressBar->advance();

        $progressBar->finish();
        $this->info("\nProject processing completed successfully.");
    }

    protected function updateAllStructures()
    {
        $this->info('Updating all structures...');
        Artisan::call('app:structures');
        Artisan::call('app:index');
        $this->info('All structures updated successfully.');
    }

    protected function getInvoiceTypeIds($selectedConfig)
    {
        $invoiceTypeIds = [];
        foreach ($selectedConfig as $ref) {
            $invoiceType = InvoiceType::where('ref', $ref)->first();
            if ($invoiceType) {
                $invoiceTypeIds[] = $invoiceType->id;
            }
        }
        return $invoiceTypeIds;
    }
}