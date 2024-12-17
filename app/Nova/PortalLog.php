<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\LoggableTypeFilter;
use App\Nova\Filters\ActionTypeFilter;

class PortalLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PortalLog>
     */
    public static $model = \App\Models\PortalLog::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'action';

    public static function label()
    {
        return 'Системные логи';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $searchable = false;

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'portal-logs';
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
            ID::make()->sortable(),
            
            MorphTo::make('Related', 'loggable')
                ->types([
                    Design::class,
                    User::class
                ])->hideFromIndex(),

            Text::make('Action')
                ->sortable(),

            Text::make('Action Type')
                ->sortable(),
            
            Code::make('Details')
                ->json()
                ->hideFromIndex(),

            BelongsTo::make('User')->hideFromIndex(),
            
            Date::make('Created At')
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new LoggableTypeFilter,
            new ActionTypeFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
