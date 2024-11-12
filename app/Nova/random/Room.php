<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;

class Room extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Room';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Rooms');
    }


    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Select::make('Size')->options([
                'Sk' => 'Скат',
                'St' => 'Стропила',
                'En' => 'Ендовы',
                'Kr' => 'Кровля',
            ]),
            Number::make("Ширина/Шт", "measure_1"),
            Number::make('Длинна', "measure_2"),
        ];
    }
}
