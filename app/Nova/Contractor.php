<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Textarea;
use App\Http\Controllers\RuTranslationController as Translator;
use Laravel\Nova\Fields\Email;
use Outl1ne\NovaHiddenField\HiddenField;

class Contractor extends Resource
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
        return Translator::translate('contractor_menu_label');
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', '=', 'contractor'); // or 'contractor' for the ContractorResource
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

            Email::make(Translator::translate('email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),

            Text::make(Translator::translate('phone_1'), 'phone_1')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(Translator::translate('phone_2'), 'phone_2')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make(Translator::translate('message'), 'message')
                ->sortable()
                ->rules('nullable', 'max:65535'),

            Hidden::make('type')->default('contractor'),
        ];
    }

    // Include any other methods as required for cards, filters, lenses, and actions
}
