<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Caption extends Resource
{
    public static $model = \App\Models\Caption::class;
    public static $title = 'key';
    public static $search = ['key', 'value'];

    public static function label()
    {
        return 'Тексты и переводы';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Key')->sortable()->rules('required', 'max:255'),
            Textarea::make('Value')->rules('required'),
            Text::make('Locale')->sortable()->rules('required', 'max:5'),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}