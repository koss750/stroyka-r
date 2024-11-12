<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use App\Models\PricePlan as PricePlanModel;


class PricePlan extends Resource
{
    public static $model = PricePlanModel::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'code'
    ];

    public static function label() 
    {
        return 'Цены услуг';
    }

    public static function singularLabel()
    {
        return 'Цена за услугу';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Название', 'title')->sortable(),
            Text::make('Описание', 'description')->readonly()->hideFromIndex(),
            Boolean::make('Фиксированная цена', 'static_price')->readonly()->hideFromIndex(),
            Select::make('Зависимая сущность', 'dependent_entity')
                ->options([
                    'Design' => 'Design',
                    'Supplier' => 'Supplier',
                    'Foundation' => 'Foundation',
                ])
                ->hideWhenUpdating(function ($request) {
                    return $this->static_price;
                })->hideFromIndex(),
            Text::make('Зависимый параметр', 'dependent_parameter')
                ->hideWhenUpdating(function ($request) {
                    return $this->static_price;
                })->hideFromIndex(),
            Select::make('Тип зависимости', 'dependent_type')
                ->options([
                    'range' => 'Range',
                    'specific' => 'Specific',
                ])
                ->hideWhenUpdating(function ($request) {
                    return $this->static_price;
                })->hideFromIndex(),
            $this->priceField(),
            Number::make('Срок действия (дни)', 'validity_days')->rules('required', 'integer', 'min:1'),
            Boolean::make('Активен', 'is_active')->hideFromIndex(),
            Number::make('Лимит использований', 'limit_uses')->hideFromIndex(),
            DateTime::make('Действителен с', 'valid_from')->readonly(),
        ];
    }

    protected function priceField()
    {
        if ($this->static_price) {
            return Number::make('Цена', 'price')->rules('required', 'min:0')->hideFromIndex();
        }

        if ($this->dependent_type === 'range') {
            return SimpleRepeatable::make('Ценовые диапазоны', 'parameter_option', [
                Number::make('От', 'from')->rules('required', 'min:0'),
                Number::make('До', 'to')->rules('nullable', 'min:0'),
                Number::make('Цена', 'price')->rules('required', 'min:0'),
            ])->hideFromIndex();
        }

        if ($this->dependent_type === 'specific') {
            // You might need to dynamically generate fields based on the dependent entity and parameter
            return SimpleRepeatable::make('Цены', 'parameter_option', [
                Text::make('Значение', 'value')->rules('required'),
                Number::make('Цена', 'price')->rules('required', 'min:0'),
            ])->hideFromIndex();
        }

        return null;
    }
}
