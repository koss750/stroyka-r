<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RuTranslationController as Translator;

class CheckPrices extends Action
{
    use InteractsWithQueue, Queueable;


    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return Translator::translate('check_prices_action');
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
    
        return Action::message('1 235 653');
        
    }


    public function fields(NovaRequest $request)
    {
        return [
            
            Select::make('Фундамент', 'sheet_type')
                ->options([
                    'fnone' => 'Без Фундамента',
                    'fLenta' => 'Ленточный тест',
                    'fLent' => 'Ленточный',
                    'fVinta' => 'Винтовой и ЖБ Сваи',
                    'fMono' => 'Монолитная плита'
                    // You can add more sheet types here in the future
                ])->help('Выберите тип фундамента')
                ->displayUsingLabels(),
            Select::make('Сечения', 'tape_width')
            ->options([
                '300x600' => '300x600',
                '300x700' => '300x700',
                '300x800' => '300x800',
                '300x900' => '300x900',
                '300x1000' => '300x1000',
                '400x600' => '400x600',
                '400x700' => '400x700',
                '400x800' => '400x800',
                '400x900' => '400x900',
                '400x1000' => '400x1000',
                '500x600' => '500x600',
                '500x700' => '500x700',
                '500x800' => '500x800',
                '500x900' => '500x900',
                '500x1000' => '500x1000',
                '600x700' => '600x700',
                '600x800' => '600x800',
                '600x900' => '600x900',
                '600x1000' => '600x1000',
            ])
            ->displayUsingLabels()
            ->sortable()
            ->help('Выберите ширину ленты (только ленточный фундамент)'),
        Select::make('Домокомплект', 'home_type')
            ->options([
                'kBrus' => 'Брус',
                'kOCB' => 'ОЦБ',
                'kRubka' => 'Ручная Рубка',
                'fMono' => 'Монолитная плита'
                // You can add more sheet types here in the future
            ])->help('Выберите домокомплект')
            ->displayUsingLabels(),
        Select::make('Материал', 'material_type')
                ->options([
                    'mSosna' => 'Лиственница',
                    'mList' => 'Сосна',
                    'mKedr' => 'Кедр'
                ])->help('Выберите тип (только брус и ОЦБ)')
                ->displayUsingLabels(),
        Select::make('Диаметр', 'material_width')
                ->options([
                    '200' => '200',
                    '220' => '220',
                    '240' => '240',
                    '260' => '260',
                    '280' => '280',
                    '300' => '300'
                    ])->help('Выберите диаметр')
                    ->displayUsingLabels(),
        Select::make('Кровля', 'krovla')
                ->options([
                    'Мягкая' => '200',
                    'Метал' => '220',
                    'Рубероид' => '240'
                    ])->help('Выберите тип')
                    ->displayUsingLabels(),
            ];
            }
        }
        
        
        

        