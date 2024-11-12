<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DesignSeo;
use App\Models\Design;

class DesignGenerateMetaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'misc:generate-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $designSeos = DesignSeo::whereNotNull('additional_meta')->get();

        foreach ($designSeos as $designSeo) {
            $additionalMeta = $designSeo->additional_meta;

            if (isset($additionalMeta['title'])) {
                $designSeo->title = $additionalMeta['title'];
            }

            if (isset($additionalMeta['text'])) {
                $designSeo->alt_description = $additionalMeta['text'];
            }

            if (isset($additionalMeta['meta_description'])) {
                $designSeo->description = $additionalMeta['meta_description'];
            }

            if (isset($additionalMeta['meta_keywords'])) {
                $designSeo->keywords = $additionalMeta['meta_keywords'];
            }

            // Set additional_meta back to null
            $designSeo->additional_meta = null;

            // Save the changes
            $designSeo->save();

            $this->info("Processed DesignSeo ID: {$designSeo->id}");
        }

        /* Additional processing for description set but alt_title isn't
        $designSeos = DesignSeo::whereNotNull('description')
                               ->whereNull('alt_title')
                               ->get();

        foreach ($designSeos as $designSeo) {
            $design = Design::find($designSeo->design_id);

            if ($design) {
                $designSeo->alt_title = $designSeo->title;
                $designSeo->title = $design->title;

                // Save the changes
                $designSeo->save();

                $this->info("Updated alt_title for DesignSeo ID: {$designSeo->id}");
            }
        }
        */
        $this->info('All relevant DesignSeo records have been processed.');
    }
}
