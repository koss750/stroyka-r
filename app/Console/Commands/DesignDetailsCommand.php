<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Design;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Models\ProjectPrice;
use App\Models\InvoiceType;
use App\Models\PortalLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesignDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * @var string
     */
    protected $signature = 'misc:fix-design-details {design_id? : The ID of the design}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Current custom script';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $designId = $this->argument('design_id');
        if (isset($designId)) {
            $this->updateOne($designId);
        } else {
            //$this->updateAll();
            $this->removeStalePrices();
        }
    }

    public function updateOne($id) {
        $design = Design::find($id);
        $currentDetails = $design->details;
        $newArray = [];
        if (is_array($currentDetails)) {
            foreach ($currentDetails as $key => $value) {
                $newArray[$key] = $value;
            }
        }
        $this->info("Updating details for design ID {$design->id} ($design->title)...");
        if (strpos($design->title, 'ОЦБ') !== false) {
            $newArray['defaultRef'] = 471;
            $newArray['defaultParent'] = 211;
        } else if (strpos($design->title, 'ПБ') !== false) {
            $newArray['defaultRef'] = 456;
            $newArray['defaultParent'] = 213;
        }
        if (!isset($newArray['price'])) {
            $invoiceType = InvoiceType::where('ref', $newArray['defaultRef'])->firstOrFail();
            $projectPrice = ProjectPrice::where('invoice_type_id', $invoiceType->id)->where('design_id', $design->id)->latest()->first();
            $newArray['price'] = json_decode($projectPrice->price);
        }
        $design->details = json_encode($newArray);
        $design->save();
        PortalLog::create([
            'loggable_type' => get_class($design),
            'loggable_id' => $design->id,
            'action' => 'Добавлен в сайт',
            'action_type' => 'app:fix-design-details',
            'user_id' => auth()->id() ?? 7,
        ]);
        $this->info("Details updated for design ID {$design->id} ($design->title)");
    }

    public function updateAll() {
            $this->info("Updating details for all designs...");
            $designs = Design::where('active', 1)->get();
            foreach ($designs as $design) {
                $this->updateOne($design->id);
        }
    }

    public function removeStalePrices() {
        $this->info('Starting cleanup of duplicate project prices...');

        // Get all active designs
        $designs = Design::where('active', 1)->get();
        $bar = $this->output->createProgressBar(count($designs));
        
        $totalDeleted = 0;

        foreach ($designs as $design) {
            // Get all project prices for this design grouped by invoice_type_id
            $duplicates = ProjectPrice::where('design_id', $design->id)
                ->select('invoice_type_id', DB::raw('COUNT(*) as count'))
                ->groupBy('invoice_type_id')
                ->having('count', '>', 1)
                ->get();

            foreach ($duplicates as $duplicate) {
                // Get all prices for this invoice type except the latest one
                $toDelete = ProjectPrice::where('design_id', $design->id)
                    ->where('invoice_type_id', $duplicate->invoice_type_id)
                    ->orderBy('created_at', 'desc')
                    ->skip(1) // Skip the latest one
                    ->take($duplicate->count - 1) // Take all others
                    ->delete();

                $totalDeleted += $toDelete;
            }

            $bar->advance();
        }

        $bar = $this->output->createProgressBar(count($designs));
        foreach ($designs as $design) {
            // Get all project prices for this design grouped by invoice_type_id
            $duplicates = PortalLog::where('loggable_type', 'App\Models\Design')
                ->where('loggable_id', $design->id)
                ->select('action', DB::raw('COUNT(*) as count'))
                ->groupBy('action')
                ->having('count', '>', 1)
                ->get();

            foreach ($duplicates as $duplicate) {
                // Get all prices for this invoice type except the latest one
                $toDelete = PortalLog::where('loggable_type', 'App\Models\Design')
                    ->where('loggable_id', $design->id)
                    ->where('action', $duplicate->action)
                    ->orderBy('created_at', 'desc')
                    ->skip(1) // Skip the latest one
                    ->take($duplicate->count - 1) // Take all others
                    ->delete();

                $totalDeleted += $toDelete;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Cleanup complete! Deleted {$totalDeleted} duplicate entries.");
    }
}