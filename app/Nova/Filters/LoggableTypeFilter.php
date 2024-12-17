<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class LoggableTypeFilter extends Filter
{
    public $name = 'Entity Type';

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('loggable_type', $value);
    }

    public function options(NovaRequest $request)
    {
        return [
            'Проект' => 'App\\Models\\Design',
            'Пользователь' => 'App\\Models\\User',
            'Смета по проекту' => 'App\\Models\\ProjectPrice',
        ];
    }
}