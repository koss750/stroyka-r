<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Design;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Carbon\Carbon;

class ImageConversionCommand extends Command
{
    protected $signature = 'misc:convert-images {design_id? : The ID of the specific design to process} {--recent : Process designs from the last 7 days}';

    protected $description = 'Generate conversions for all missing media attached to Design models';

    public $timeout = 130; // 2 minutes

    public function retryUntil()
    {
        return Carbon::now()->addMinutes(1);
    }

    public function handle()
    {
        $designId = $this->argument('design_id');
        $recentOnly = $this->option('recent');

        $query = Design::where('active', 1);

        if ($designId) {
            $query = Design::where('id', $designId);
        } elseif ($recentOnly) {
            $query = Design::where('updated_at', '>=', Carbon::now()->subDays(10));
        }

        $designs = $query->get();

        if ($designs->isEmpty()) {
            $this->error($designId ? "No active design found with ID: {$designId}" : "No designs found matching the criteria");
            return;
        }

        $this->info("Found {$designs->count()} active Design model(s)");

        $bar = $this->output->createProgressBar($designs->count());

        foreach ($designs as $design) {
            $this->info("\nProcessing Design id: {$design->id}");
            $images = $design->getMedia('images');
            foreach ($images as $image) {
                $this->info("\nProcessing Media id: {$image->id}");
                $conversionPath = $image->getPath().'/conversions';
                
                if (!file_exists($image->getPath())) {
                    $this->warn("Skipping media id: {$image->id} - Original file not found");
                    continue;
                }
        
                // Remove this check to allow re-processing of existing conversions
                // if (file_exists($conversionPath) && is_dir($conversionPath) && count(scandir($conversionPath)) > 2) {
                //     $this->info("Skipping media id: {$image->id} - Conversions already exist");
                //     continue;
                // }
                
                $this->info("Queueing conversions for media id: {$image->id}");
                
                // Re-register media conversions
                $design->registerMediaConversions($image);
                
                // Get the conversions for this media
                $conversions = ConversionCollection::createForMedia($image);
                
                // Queue the conversion job
                dispatch(new PerformConversionsJob($conversions, $image));
                
                $this->info("Conversions queued for media id: {$image->id}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAll conversions for Design media have been queued for processing.");
    }
}