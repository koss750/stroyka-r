<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Design as Design; // Assuming this is your Design model
use App\Models\DesignMaterial as DM; // Assuming this is your DesignMaterial model

class DesignPriceManager extends Resource
{
    public static $model = 'App\Models\Design'; 

    public static $title = 'id';

    public static function label()
    {
        return 'Администрация';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Select::make('Design')
                ->options(Design::all()->pluck('name', 'title'))
                ->displayUsingLabels(),

            // Assuming you have a method in your Design model that can summarize current prices
            Text::make('Current Prices', function () {
                return "В разработке";
            }),

            // Fields to add new prices
            // This could be a HasMany field linked to a related model, or custom fields for inputting prices

            // Custom action to handle the price update logic
        ];
    }

    // You can define methods to handle custom logic as needed
}
