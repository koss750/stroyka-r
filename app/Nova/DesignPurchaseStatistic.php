<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Design;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DesignPurchaseStatistic extends Resource
{
    public static $model = Design::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title',
    ];

    // do not allow editing
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Проект', 'title')->sortable(),
            Number::make('Покупки', 'purchases')->sortable(),
            Number::make('Просмотры', 'view_count')->sortable(),
            Currency::make('Выручка', 'revenue')->sortable(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->select('designs.id', 'designs.title', 'designs.view_count')
            ->selectRaw('COUNT(projects.id) as purchases')
            ->selectRaw('SUM(projects.payment_amount) as revenue')
            ->leftJoin('projects', 'designs.id', '=', 'projects.design_id')
            ->groupBy('designs.id', 'designs.title', 'designs.view_count')
            ->havingRaw('COUNT(projects.id) > 0')
            ->orderByDesc('purchases');
    }

    public static function label()
    {
        return 'Покупки по проектам';
    }

    public static function uriKey()
    {
        return 'design-purchase-statistics';
    }

    //по временным периодам
    //фильтр по категориям

}