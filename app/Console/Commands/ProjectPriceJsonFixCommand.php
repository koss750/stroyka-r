<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectPrice;
use App\Models\Design;

class ProjectPriceJsonFixCommand extends Command
{
    protected $signature = 'misc:fixprice';
    protected $description = 'Fix double-encoded JSON in project prices';

    public function handle()
    {
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
    }
}