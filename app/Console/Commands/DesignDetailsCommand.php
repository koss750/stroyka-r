<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Design;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RuTranslationController as Translator;

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
            $this->updateAll();
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
            $newArray['price'] = 999;
        }
        $design->details = json_encode($newArray);
        $design->save();
        $this->info("Details updated for design ID {$design->id} ($design->title)");
    }

    public function updateAll() {
            $this->info("Updating details for all designs...");
            $designs = Design::all();
            foreach ($designs as $design) {
                $this->updateOne($design->id);
        }
    }
}