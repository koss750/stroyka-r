<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;


class TurboPageController extends Controller
{
    public function indexAllProjects()
    {
        $designs = Design::latest()->where('active', 1)->get();
        foreach($designs as $design) {
            $url = url("/project/{$design->id}");
            echo "$url" . "<br>";
        }
    }

    public function generateRssFeed()
    {
        // Try to get from cache first
        $rssData = Cache::get('turbo_rss_feed');
        if (!$rssData) {
            // If not in cache, generate the feed
            $designs = Design::latest()->where('active', 1)->get()->map(function($design) {
                $design->etiketka = json_decode($design->details, true)["price"];
                $design->etiketka = round($design->etiketka, 2);
                $design->etiketka = number_format($design->etiketka, 2, '.', ' ');
                $design->image_url = $design->mildMailImage();
                return $design;
            });

            $rssData = view('rss.turbo-pages', compact('designs'))->render();

            // Store in cache for 7 days
            Cache::put('turbo_rss_feed', $rssData, now()->addDays(7));
        }

        return Response::make($rssData, 200, [
            'Content-Type' => 'application/rss+xml',
        ]);
    }
}
