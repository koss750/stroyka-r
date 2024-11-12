<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Http\Controllers\RuTranslationController as Translator;

class ProjectType extends Resource
{
    public static $model = \App\Models\ProjectType::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'category', 'size',
    ];

    public static function label()
    {
        return 'Цены смет';
    }

    public function fields(NovaRequest $request)
    {
        return [

            Text::make(Translator::translate('category'), function () {
                return Translator::translate($this->category);
            })->sortable()
                ->readonly(),

            Text::make('Size')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            Number::make('Price')
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('category')->orderBy('size');
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}