<?php

namespace App\Services;

use App\Models\Caption;
use Illuminate\Support\Facades\Cache;

class CaptionService
{
    public function get($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $cacheKey = "caption:{$locale}:{$key}";

        return Cache::remember($cacheKey, now()->addDay(), function () use ($key, $locale) {
            return Caption::where('key', $key)
                ->where('locale', $locale)
                ->value('value') ?? $key;
        });
    }

    public function all($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $cacheKey = "captions:{$locale}";

        return Cache::remember($cacheKey, now()->addDay(), function () use ($locale) {
            return Caption::where('locale', $locale)->pluck('value', 'key')->toArray();
        });
    }

    public function update($key, $value, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $caption = Caption::updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['value' => $value]
        );

        $this->clearCache($key, $locale);

        return $caption;
    }

    private function clearCache($key, $locale)
    {
        Cache::forget("caption:{$locale}:{$key}");
        Cache::forget("captions:{$locale}");
    }
}