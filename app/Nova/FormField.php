<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use App\Nova\Filters\FoundationFilter;

class FormField extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FormField>
     */
    public static $model = \App\Models\FormField::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Фундаменты (поля)';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Select::make('Тип фундамента', 'form_type')
                ->options([
                    'lenta' => 'Ленточный фундамент',
                    'srp' => 'Свайно-растверковый фундамент с плитой',
                    'sr' => 'Свайно-растверковый фундамент',
                    'lp' => 'Ленточный фундамент с плитой',
                    'mp' => 'Монолитная плита',
                ])
                ->displayUsing(function ($value) {
                    $options = [
                        'lenta' => 'Ленточный фундамент',
                        'srp' => 'Свайно-растверковый фундамент с плитой',
                        'sr' => 'Свайно-растверковый фундамент',
                        'lp' => 'Ленточный фундамент с плитой',
                        'mp' => 'Монолитная плита',
                    ];
                    return $options[$value] ?? $value;
                })
                ->sortable(),

            Text::make('Имя поля', 'name')->onlyOnForms(),
            Text::make('Текст поля', 'label'),
            Select::make('Тип поля', 'type')->options([
                'text' => 'Текст',
                'number' => 'Число',
                'select' => 'На выбор',
            ])->onlyOnForms(),
            Text::make('Excel ячейка', 'excel_cell'),
            Textarea::make('Подсказка', 'tooltip'),
            Text::make('Значение в поле', 'default'),
            Text::make('Варианты', 'options'),
            Text::make('Валидация', 'validation')->onlyOnForms(),
            Number::make('Порядок', 'order')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new FoundationFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static $defaultSort = null;

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('order');
    }
}