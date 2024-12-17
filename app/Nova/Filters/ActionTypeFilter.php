<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\DB;

class ActionTypeFilter extends Filter
{
    public $name = 'Action Type';

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('action_type', $value);
    }

    public function options(NovaRequest $request)
    {
        // Dynamically get all action types from the database
        return DB::table('portal_logs')
            ->select('action_type')
            ->distinct()
            ->pluck('action_type', 'action_type')
            ->toArray();
    }
}