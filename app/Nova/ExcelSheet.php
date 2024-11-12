<?php
namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use app\Models\ExcelSheet as Model;

class ExcelSheet extends Resource
{
    public static $model = 'App\Models\ExcelSheet';

    public static $title = 'name';

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Description', 'description')
                ->hideFromIndex(), // Optional: hide in index view if too many columns

            Text::make('Type', 'type'),

            // For params, you might want to use a custom field type or JSON field
        ];
    }
}