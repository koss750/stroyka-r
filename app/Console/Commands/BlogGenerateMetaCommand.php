<?php

namespace App\Console\Commands;

use App\Models\Design;
use App\Models\DesignSeo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\BlogPost as Blog;

class BlogGenerateMetaCommand extends Command
{
    protected $signature = 'blog:generate-meta {blog_id? : The ID of the blog to process}';

    protected $description = 'Generate short description for blog using YandexGPT';

    public function handle()
    {
        $blogId = $this->argument('blog_id');

        if ($blogId) {
            $blog = Blog::find($blogId);
            if (!$blog) {
                $this->error("Blog with ID {$blogId} not found.");
                return 1;
            }
            $this->generateShortDescription($blog);
            $this->info("Short description generated for blog {$blogId}.");
        } else {
            $blogs = Blog::where('are_tags_generated', 0)->get();
            $count = $blogs->count();
            $this->info("Generating short description for {$count} blogs...");

            $progress = $this->output->createProgressBar($count);
            foreach ($blogs as $blog) {
                $this->generateShortDescription($blog);
                $progress->advance();
                sleep(0.5);
            }
            $progress->finish();
            $this->newLine();
            $this->info("Short description generation completed for all blogs.");
        }
    }

    private function generateShortDescription(Blog $blog)
    {
        $description = strip_tags($blog->content);
        
        // Prepare the data for the YandexGPT API request
        $apiKey = config('services.yandex.api_key'); // Make sure to add this to your config
        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';
        
        $data = [
            'modelUri' => 'gpt://b1g9djuppc9vcq966ndt/yandexgpt-lite',
            'completionOptions' => [
                'stream' => false,
                'temperature' => 0.6,
                'maxTokens' => '100'
            ],
            'messages' => [
                [
                    'role' => 'system',
                    'text' => 'You read a blog article (between <start> / <end> tags) and generate a short TLDR introduction for SEO purposes up to 200 chars'
                ],
                [
                    'role' => 'user',
                    'text' => "<start>{$description}<end>"
                ]
            ]
        ];

        /* get from cache if exists
        //$response = Cache::get('yandex_response_' . $design->id);
        //$response = null;
        if ($response) {
            $this->info("Got response from cache");
        } else {
         */
        $response = Http::withHeaders([
            'Authorization' => 'Api-Key ' . $apiKey,
            'Content-Type' => 'application/json'
        ])->post($url, $data);
        Cache::put('yandex_response_' . $blog->id, $response->json(), 100);
        

        if ($response->successful()) {
            $result = $response->json();
            $generatedContent = $result['result']['alternatives'][0]['message']['text'] ?? '';
            $blog->short_description = $generatedContent;
            $blog->are_tags_generated = 1;
            $blog->save();
        } else {
            Log::error('YandexGPT API request failed: ' . $response->body());
        }
    }
}