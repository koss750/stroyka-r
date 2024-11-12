<?php

namespace App\Nova;

use App\Models\ExcelFileType; // Assuming this is the correct namespace
use Laravel\Nova\Fields\ID;
use App\Nova\AssociatedCost;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use App\Http\Controllers\RuTranslationController as Translator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Laravel\Nova\Panel;

class AssociatedCost extends Resource
{

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        $labelCaption = 'templates_and_costs_nova';
        return Translator::translate($labelCaption); 
    }

    /**
     * Get the singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        $labelCaption = 'templates_and_costs_nova';
        return Translator::translate($labelCaption); 
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ExcelCosts>
     */
    public static $model = \App\Models\ExcelFileType::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'file';


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static function search(NovaRequest $request, $query)
    {
        return $query->where('type', 'like', $request->search . '%')
                     ->orWhere('subtype', 'like', $request->search . '%')
                     ->orWhere('file', 'like', $request->search . '%');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {

        $static = [
            Text::make(Translator::translate('tmp_subtype_label'), 'subtype')
            ->displayusing(function($value) {
                return Translator::translate($value);
            })->sortable()
                ->rules('required', 'max:30'),
    
            Text::make(Translator::translate('tmp_file_label'), "file")
            ->displayusing(function($value) {
                return Translator::translate($value);
            })->sortable()
                ->rules('required', 'max:30')];
        $fields = [];
        $fields = array_merge($static, $fields);
        $associatedCosts = $this->associatedCosts;

    if ($associatedCosts) {
        // Collect unique p_types
        $pTypes = collect($associatedCosts)->pluck('p_type')->unique();

        foreach ($pTypes as $pType) {
            // Filter associatedCosts for this pType
            $filteredCosts = collect($associatedCosts)->where('p_type', $pType)->values();

            // Add to fields array
            $fields[] = new Panel(Translator::translate($pType . '_panel_label'), [
                SimpleRepeatable::make(Translator::translate('ass_co_panel_label_' . $pType), 'associatedCosts', [
                    Text::make(Translator::translate('ass_co_p_title_label'), 'p_title'),
                    Number::make(Translator::translate('ass_co_p_value_label'), 'p_value'),
                    Hidden::make('','p_code'),
                    Hidden::make('','p_cell')
                ])->stacked()->canAddRows(true)->addRowLabel(Translator::translate('table_add_field_label'))
            ]);
        }
    }

    
                
            //Original hasmany field
            //HasMany::make(Translator::translate('ass_co_panel_label'), 'associatedCosts', 'App\Nova\AssociatedCost'),
            //section	p_type	p_title	p_value	p_cell	p_code
            return $fields;

            
        
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
}
