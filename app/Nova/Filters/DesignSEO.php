<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Nova;

class DesignSEO extends BooleanFilter
{
    public function apply(Request $request, $query, $value)
    {
        if ($value['has_seo']) {
            return $query->whereNotNull('description')
                      ->orWhereNotNull('keywords')
                      ->orWhereNotNull('additional_meta');
        }

        if ($value['no_seo']) {
            return $query->whereNull('description')
                         ->whereNull('keywords')
                         ->whereNull('additional_meta');
        }

        return $query;
    }

    public function options(Request $request)
    {
        return [
            'Есть SEO' => 'has_seo',
            'Без SEO' => 'no_seo',
        ];
    }
}