<?php

namespace App\Console\Commands;

use App\Models\Design;
use App\Models\DesignSeo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DesignSeedSEOCommand extends Command
{
    protected $signature = 'misc:generate-seo {design_id? : The ID of the design to process}';

    protected $description = 'Generate SEO content for designs using YandexGPT';

    public function handle()
    {
        $designId = $this->argument('design_id');

        if ($designId) {
            $design = Design::find($designId);
            if (!$design) {
                $this->error("Design with ID {$designId} not found.");
                return 1;
            }
            $this->generateSEO($design);
            $this->info("SEO content generated for design {$designId}.");
        } else {
            $designs = Design::where('active', 1)->get();
            $count = $designs->count();
            $this->info("Generating SEO content for {$count} designs...");

            $progress = $this->output->createProgressBar($count);
            foreach ($designs as $design) {
                $this->generateSEO($design);
                $progress->advance();
            }
            $progress->finish();
            $this->newLine();
            $this->info("SEO content generation completed for all designs.");
        }
    }

    private function generateSEO($design)
    {
        $seo = DesignSeo::firstOrCreate(['design_id' => $design->id]);
        
        // Prepare the data for the YandexGPT API request
        $apiKey = config('services.yandex.api_key'); // Make sure to add this to your config
        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';

        $price = json_decode($design->details);
        $price = $price->price->material;
        if ($price == "999") {
            $price = "unavailable";
        }
        
        $data = [
            'modelUri' => 'gpt://b1g9djuppc9vcq966ndt/yandexgpt-lite',
            'completionOptions' => [
                'stream' => false,
                'temperature' => 0.6,
                'maxTokens' => '200'
            ],
            'messages' => [
                [
                    'role' => 'system',
                    'text' => 'You are an SEO expert. Generate a description meta tag for the given building project in Russian. The description should be between 150 and 160 characters. Start the description with "Сметы на строительство" followed by the type of building (дом or баня, based on the first letter of the title), material (бревно if ОЦБ is in the name, брус if ПБ is in the name), size, and area. Include the price if available. Do not make up any numbers, only use those given. Respond with the description text only, without any HTML tags or additional formatting.'
                ],
                [
                    'role' => 'user',
                    'text' => "Title is {$design->title}. Area in m2: {$design->size}. And the overall size is {$design->length} на {$design->width}. price is {$price}. meta tags are to be in Russian. the website overall is there to sell смета for building projects which can then be forwarded to a builder. prices for materials are current and up to date"
                ]
            ]
        ];

        // get from cache if exists
        //$response = Cache::get('yandex_response_' . $design->id);
        $response = null;
        if ($response) {
            $this->info("Got response from cache");
        } else {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post($url, $data);
            Cache::put('yandex_response_' . $design->id, $response->json(), 100);
        }
        

        if ($response->successful()) {
            $result = $response->json();
            
            $generatedContent = $result['result']['alternatives'][0]['message']['text'] ?? '';
            $seo->title = "Проект $design->title, размер $design->size м2, $design->lengt на $design->width";
            $seo->description = $generatedContent;
            $seo->save();
        } else {
            Log::error('YandexGPT API request failed: ' . $response->body());
        }
    }
}