<?php

namespace App\Nova;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Design;
use App\Models\InvoiceType; 
use App\Nova\InvoiceType as InvoiceTypeNova;
use App\Nova\Design as DesignNova;
use Illuminate\Http\Request;

class ProjectPrice extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProjectPrice::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    //do not display on menu
    public static $displayInNavigation = false;
    public static $perPageViaRelationship = 140;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'invoice_type_id', 'InvoiceType.sheetname'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $invoiceType = InvoiceType::find($this->invoice_type_id);
        return [
            ID::make()->hide(),
            Text::make('Смета', function() {
                $fullString = '';
                $fullString .= $this->invoiceType->site_tab_label;
                $fullString .= ' - ' . $this->invoiceType->site_label;
                $fullString .= ' - ' . $this->invoiceType->site_sub_label ?? '';
                if ($this->invoiceType->site_level4_label != "TRUE" && $this->invoiceType->site_level4_label != "FALSE") {
                    $fullString .= ' - ' . $this->invoiceType->site_level4_label ?? '';
                }
                return $fullString;
            })->readonly(),
            Panel::make('Цены', function() {
                $fields = [];
                
                if ($this->price) {
                    $price = json_decode($this->price, true);
                    foreach ($price as $key => $value) {
                        switch ($key) {
                            case 'material':
                                $key = 'Материал';
                                break;
                            case 'labour':
                                $key = 'Работа';
                                break;
                            case 'total':
                                $key = 'Итого';
                                break;
                        }
                        $fields[] = Text::make($key)
                            ->displayUsing(fn() => number_format($value, 2) . ' ₽')
                            ->readonly();
                    }
                }
                
                return $fields;
            }),
            $this->generateSmetaPanel()
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

    private function generateSmetaPanel() {
        $fields = [];
        
        if ($this->parameters) {
            $parameters = json_decode($this->parameters, true);
            
            if (isset($parameters['sheet_structure']['sections'])) {
                foreach ($parameters['sheet_structure']['sections'] as $sectionKey => $section) {
                    // Add section header
                    $fields[] = Text::make($section['value']);
                    
                    // Labour items panel
                    if (!empty($section['labourItems'])) {
                        $fields[] = Panel::make('Работы', function() use ($section) {
                            $labourFields = [];
                            foreach ($section['labourItems'] as $item) {
                                if (!empty($item['labourTitle']) && $item['labourTotal'] > 0) {
                                    $labourFields[] = Text::make($item['labourTitle'])
                                        ->displayUsing(fn() => number_format($item['labourTotal'], 2) . ' ₽')
                                        ->readonly();
                                }
                            }
                            $labourFields[] = Text::make('Итого по работам')
                                ->displayUsing(fn() => number_format($section['sectionTotalLabour'], 2) . ' ₽')
                                ->readonly();
                            return $labourFields;
                        });
                    }
                    
                    // Material items panel
                    if (!empty($section['materialItems'])) {
                        $fields[] = Panel::make('Материалы', function() use ($section) {
                            $materialFields = [];
                            foreach ($section['materialItems'] as $item) {
                                if (!empty($item['materialTitle']) && $item['materialTotal'] > 0) {
                                    $materialFields[] = Text::make($item['materialTitle'])
                                        ->displayUsing(fn() => number_format($item['materialTotal'], 2) . ' ₽')
                                        ->readonly();
                                }
                            }
                            $materialFields[] = Text::make('Итого по материалам')
                                ->displayUsing(fn() => number_format($section['sectionTotalMaterial'], 2) . ' ₽')
                                ->readonly();
                            return $materialFields;
                        });
                    }
                    
                    // Section total
                    $fields[] = Text::make('Итого по разделу')
                        ->displayUsing(fn() => number_format($section['sectionTotalValue'], 2) . ' ₽')
                        ->readonly();
                }
                
                // Grand totals
                if (isset($parameters['totals'])) {
                    $fields[] = Text::make('ИТОГО Работы')
                        ->displayUsing(fn() => number_format($parameters['totals']['labour'], 2) . ' ₽')
                        ->readonly();
                    $fields[] = Text::make('ИТОГО Материалы')
                        ->displayUsing(fn() => number_format($parameters['totals']['material'], 2) . ' ₽')
                        ->readonly();
                    $fields[] = Text::make('ИТОГО')
                        ->displayUsing(fn() => number_format($parameters['totals']['total'], 2) . ' ₽')
                        ->readonly();
                }
            }
        }
        
        return Panel::make('Смета', $fields);
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
        return 'project-prices';
    }

    /**
     * Determine if the current user can delete the given resource.
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can update the given resource.
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}