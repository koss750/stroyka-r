<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoiceType extends Resource
{
    public static $model = \App\Models\InvoiceType::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'ref',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title'),
            Text::make('Ref'),
            // Add other fields as needed
        ];
    }

    public static function uriKey()
    {
        return 'invoice-types';
    }
}