<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Resource;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Hidden;

class BlogPost extends Resource
{
    public static $model = \App\Models\BlogPost::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'content', 'author',
    ];

    public static function label()
    {
        return 'Блог';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Заголовок', 'title')
                ->sortable()
                ->rules('required', 'max:255'),

            Trix::make('Содержание', 'content')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Краткое описание (необязательно)', 'short_description')
                ->hideFromIndex(),

            Text::make('Автор (необязательно)', 'author')
                ->sortable()
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make('Теги через запятую', 'tags')
                ->displayUsing(function ($tags) {
                    return is_array($tags) ? implode(', ', $tags) : $tags;
                })
                ->hideFromIndex(),

            Number::make('Количество просмотров', 'view_count')
                ->onlyOnIndex()
                ->sortable(),

            Boolean::make('Опубликовано', 'is_published'),

            Images::make('Изображения', 'images') // 'images' is the media collection name
                ->conversionOnIndexView('thumb')
                ->fullSize()
        ];
    }
}
