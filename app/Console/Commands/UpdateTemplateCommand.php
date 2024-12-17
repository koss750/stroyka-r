<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\PortalLog;

class UpdateTemplateCommand extends Command
{
    protected $signature = 'app:update-template';
    protected $description = 'Update the template file from API blob';

    public function handle()
    {
        $this->info('Starting template update...');

        try {
            // Download the new template
            $response = Http::get('http://tmp.mirsmet.com/api/template-blob');
            
            if (!$response->successful()) {
                throw new \Exception('Failed to download template: ' . $response->status());
            }

            $templateContent = $response->body();
            $sha256 = date('Y-m-d H:i:s');

            // check if PortalLog already exists with same sha256
            $existingLog = PortalLog::where('details', $sha256)->first();
            if ($existingLog) {
                $this->info('Template already exists in PortalLog.');
               // return 0;
            }

            // Define the path to the template file
            $templatePath = storage_path('templates/test.xlsx');

            // Create templates directory if it doesn't exist
            if (!File::exists(dirname($templatePath))) {
                File::makeDirectory(dirname($templatePath), 0755, true);
            }

            // Delete existing file if it exists
            if (File::exists($templatePath)) {
                File::delete($templatePath);
                $this->info('Deleted existing template file.');
            }

            // Save the new template
            File::put($templatePath, $templateContent);
            
            $this->info('Template file updated successfully!');
            $this->info('Path: ' . $templatePath);

            PortalLog::create([
                'loggable_type' => "Global",
                'loggable_id' => 0,
                'action' => 'Ручное обновление шаблона',
                'action_type' => 'manual-update',
                'user_id' => auth()->id() ?? 7,
                'details' => $sha256
            ]);

        } catch (\Exception $e) {
            $this->error('Failed to update template: ' . $e);
            return 1;
        }

        return 0;
    }
}