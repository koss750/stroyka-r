<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class FoundationFilter extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        //old filter for when category was not json
        //return $query
        return $query->where('form_type', $value);
    }

    public function name()
    {
        return 'Тип фундамента';
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Ленточный фундамент' => 'lenta',
            'Свайно-растверковый фундамент с плитой' => 'srp',
            'Свайно-растверковый фундамент' => 'sr',
            'Ленточный фундамент с плитой' => 'lp',
            'Монолитная плита' => 'mp',
        ];
    }
}
