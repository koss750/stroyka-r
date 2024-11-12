<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use App\Models\Design;
use App\Models\InvoiceType;
use Illuminate\Support\Facades\Log;
class PersistDesignViews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $keys = Redis::keys('design_views:*');
        
        foreach ($keys as $key) {
            $id = str_replace('design_views:', '', $key);
            $views = (int) Redis::get($key);
            try {
                Design::where('id', $id)->orWhere('slug', $id)->increment('view_count', $views);
                Redis::del($key);
            } catch (\Exception $e) {
                Log::error('Error incrementing view count for design ' . $id . ': ' . $e->getMessage());
            }
        }
        
        /*
        $invoiceKeys = Redis::keys('invoice_views:*');
        foreach ($invoiceKeys as $key) {
            $invoiceRef = str_replace('invoice_views:', '', $key);
            $views = Redis::get($key);
            Log::info('Persisting invoice views for ' . $key . ' with ' . $views . ' views');
            InvoiceType::where('ref', $invoiceRef)->increment('view_count', $views);
            Redis::del($key);
        }
        */
    }
}