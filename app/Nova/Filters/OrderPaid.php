<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;


class OrderPaid extends Filter
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
        if ($value == 'success') {
            return $query->where('payment_status', 'success');
        } else {
            return $query->where('payment_status', 'failed');
        }
    }
    public function name()
    {
        return 'Оплачено';
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
             "Оплачено" => 'success',
             "Не оплачено" => 'failed'
        ];
    }
}
