<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Actions\GenerateInvoice;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Laravel\Nova\Fields\HasOne;
use App\Nova\Filters\OrderType;
use App\Nova\Filters\OrderPaid;
use Laravel\Nova\Fields\Date;
use App\Nova\Design;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Carbon\Carbon;

class Order extends Resource
{
    public static $model = \App\Models\Project::class;

    public static $title = 'human_ref';

    public static $search = [
        'human_ref',
    ];

    // do not allow editing
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function fields(NovaRequest $request)
    {
        return [
            //ID::make()->hideFromIndex()->sortable(),
            Text::make('№', 'human_ref')->sortable(),

            BelongsTo::make('User'),

            Text::make('Тип', function () {
                return $this->order_type == 'foundation' ? 'Смета фундамента' : 'Смета проекта';
            })->sortable(),

            BelongsTo::make('Проект', 'design', Design::class),


            Text::make('IP адрес', 'ip_address')
                ->sortable()
                ->onlyOnDetail(),
            
            Text::make('Платежная система', function () {
                switch ($this->payment_provider) {
                    case 'YANDEX_SB':
                        return "Яндекс - тестовая среда";
                    case 'YANDEX':
                        return "Яндекс";
                    default:
                        return $this->payment_provider;
                }
            })
                ->sortable()
                ->onlyOnDetail(),

            Text::make('Номер чека', 'payment_reference')
                ->sortable()
                ->rules('required')
                ->onlyOnDetail(),

            Number::make('Сумма', 'payment_amount')
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01),

            Text::make('Статус', function () {
                return $this->payment_status == 'success' ? 'Оплачено' : 'Не оплачено';
            })->sortable(),

            //SimpleRepeatable::make('Выбранная конфигурация', 'selected_configuration')->onlyOnDetail(),

            File::make('Файл', 'filepath')
                ->disk('public')
                ->path('projects')
                ->prunable()
                ->deletable(),

            DateTime::make('Создано', 'created_at')
                ->displayUsing(function ($value) {
                    return $value ? Carbon::parse($value)->setTimezone('Europe/Moscow')->format('Y-m-d H:i:s') : null;
                })
                ->sortable()
        ];
    }
    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new OrderType,
            new OrderPaid,
        ];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [
        ];
    }
}