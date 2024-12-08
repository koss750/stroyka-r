<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Textarea;
use App\Http\Controllers\RuTranslationController as Translator;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Select;

class Supplier extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Supplier::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';
    
    public static function label()
    {
        return Translator::translate('supplier_menu_label');
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(Translator::translate('company_name'), 'company_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(Translator::translate('inn'), 'inn')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(Translator::translate('address'), 'address')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Физический адрес', 'physical_address')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('ОГРН', 'ogrn')
                ->sortable()
                ->rules('required', 'max:255'),

            Email::make(Translator::translate('email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),

            Text::make(Translator::translate('phone_1'), 'phone')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(Translator::translate('phone_2'), 'additional_phone')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make(Translator::translate('message'), 'message')
                ->sortable()
                ->rules('nullable', 'max:255'),

            Text::make('Status', 'status')
                ->displayUsing(function ($value) {
                    $value = $value . "_label";
                    return Translator::translate($value);
                })->onlyOnIndex(),

                Text::make('Status', 'status')
                ->displayUsing(function ($value) {
                    $value = $value . "_label";
                    return Translator::translate($value);
                })->onlyOnDetail(),

            Select::make('Status', 'status')
                ->options([
                    Translator::translate('applied_label') => Translator::translate('applied_label'),
                    Translator::translate('pending_label') => Translator::translate('pending_label'),
                    Translator::translate('approved_label') => Translator::translate('approved_label'),  
                ])->onlyOnForms(),

            Text::make('Регионы', 'regions')
                ->displayUsing(function ($value) {
                    return $value->pluck('name');
                }),
        ];
    }

    // Include any other methods as required for cards, filters, lenses, and actions
}
