<?php

namespace App\Nova;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Design;
use App\Models\InvoiceType;
use App\Models\ProjectPrice;

class ProjectPriceViewer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Design::class;

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

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Design'),

            Select::make('Invoice Type 1', 'invoice_type_1')
                ->options(InvoiceType::pluck('title', 'id'))
                ->displayUsingLabels(),

            Select::make('Invoice Type 2', 'invoice_type_2')
                ->options(InvoiceType::pluck('title', 'id'))
                ->displayUsingLabels()
                ->nullable(),

            Select::make('Invoice Type 3', 'invoice_type_3')
                ->options(InvoiceType::pluck('title', 'id'))
                ->displayUsingLabels()
                ->nullable(),

            Code::make('Price Details', function () {
                $design = Design::find($this->design_id);
                $invoiceTypeIds = array_filter([
                    $this->invoice_type_1,
                    $this->invoice_type_2,
                    $this->invoice_type_3
                ]);

                $details = [];
                foreach ($invoiceTypeIds as $index => $invoiceTypeId) {
                    $projectPrice = ProjectPrice::where('design_id', $design->id)
                        ->where('invoice_type_id', $invoiceTypeId)
                        ->first();

                    if ($projectPrice) {
                        $details["Invoice Type " . ($index + 1)] = json_decode($projectPrice->price, true);
                    }
                }

                return json_encode($details, JSON_PRETTY_PRINT);
            })
                ->json()
                ->onlyOnDetail(),
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
        return [];
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

    public static function label()
    {
        return 'Project Price Viewer';
    }

    public static function singularLabel()
    {
        return 'Project Price View';
    }

    public static function uriKey()
    {
        return 'project-price-viewer';
    }
}