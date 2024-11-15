<?php

namespace App\Jobs;

use App\Models\Design;
use App\Models\OrderFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;
use App\Models\InvoiceType;
use App\Models\ProjectPrice;

class UpdateDbPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info("Updating db prices");
        //get all the designs
        $designs = Design::where('active', 1)->get();
        $invoiceTypes = InvoiceType::where('site_level4_label', '!=', 'FALSE')->get();
        //get price from redis for each design
        foreach ($designs as $design) {
            ProjectPrice::where('design_id', $design->id)->delete();
            try {
                $details = json_decode($design->details, true);
                $details["price"] = json_decode(Redis::get($design->id), true);
                $design->update(['details' => json_encode($details)]);
                foreach ($invoiceTypes as $invoiceType) {
                    $price = Redis::get($design->id . '_' . $invoiceType->label);
                    if ($price) {
                        $projectPrice = ProjectPrice::create([
                            'design_id' => $design->id,
                            'invoice_type_id' => $invoiceType->id,
                            'price' => $price,
                        ]);
                        $price = json_decode($price, true);
                        
                    }
                    else if ($invoiceType->id == 312 || $invoiceType->id == 173) {
                        $projectPrice = ProjectPrice::create([
                            'design_id' => $design->id,
                            'invoice_type_id' => $invoiceType->id,
                            'price' => '{"labour":0,"material":0,"total":0}',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error getting price from redis for design " . $design->id . ": " . $e->getMessage());
                continue;
            }
        }
    }
}