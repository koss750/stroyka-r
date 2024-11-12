<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use App\Models\Design;
use App\Nova\Filters\DesignSEO as DesignSEOSearch;

class DesignSeo extends Resource
{
    public static $model = \App\Models\DesignSeo::class;
    public static $title = 'title';
    public static $search = ['title', 'keywords', 'description'];

    public function title()
    {
        return $this->design->title . ' - SEO';
    }

    public static function label()
    {
        return 'SEO';
    }

    public function fields(NovaRequest $request)
    {
        
        return [
            ID::make()->sortable(),
            // Add this to the fields method
            BelongsTo::make('Design'),
            Text::make('Проект', 'title')->onlyOnDetail(),
            Boolean::make('Хотя бы одно поле заполнено', function () {
                return !empty($this->description) ||
                       !empty($this->keywords) ||
                       !empty($this->alt_description) ||
                       !empty($this->additional_meta);
            })->trueValue('Заполнено')
              ->falseValue('Не заполнено')
              ->onlyOnIndex(),
            Text::make('Заголовок', 'alt_title')->hideFromIndex(),
            Textarea::make('Описание', 'description'),
            Textarea::make('meta description', 'alt_description'),
            Textarea::make('meta keywords', 'keywords'),
            Text::make('Ссылка на изображение', 'image')->hideFromIndex(),
            Text::make('Альтернативная ссылка на изображение', 'alt_image')->hideFromIndex(),
            Textarea::make('Дополнительные мета-теги', 'additional_meta'),
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
        return [new DesignSEOSearch];
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
}
