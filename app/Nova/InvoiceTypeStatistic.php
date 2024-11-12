<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\InvoiceType;
use Illuminate\Database\Eloquent\Builder;

class InvoiceTypeStatistic extends Resource
{
    public static $model = InvoiceType::class;

    public static $title = 'combined_label';

    public static $search = [
        'id', 'site_label', 'site_sub_label', 'ref',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', function () {
                return $this->site_label . ' ' . $this->site_sub_label;
            })->sortable(),
            Number::make('Использований', 'usage_count')->sortable(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->select('invoice_structures.id', 'invoice_structures.site_label', 'invoice_structures.site_sub_label', 'invoice_structures.ref')
            ->selectRaw('COUNT(projects.id) as usage_count')
            ->selectRaw("CONCAT(invoice_structures.site_label, ' ', invoice_structures.site_sub_label) as combined_label")
            ->leftJoin('projects', function ($join) {
                $join->whereRaw("projects.selected_configuration LIKE CONCAT('%', invoice_structures.ref, '%')");
            })
            ->groupBy('invoice_structures.id', 'invoice_structures.site_label', 'invoice_structures.site_sub_label', 'invoice_structures.ref')
            ->havingRaw('COUNT(projects.id) > 0')
            ->orderByDesc('usage_count');
    }

    public static function label()
    {
        return 'Статистика конфигураций';
    }

    public static function singularLabel()
    {
        return 'Статистика конфигурации';
    }

    public static function uriKey()
    {
        return 'invoice-type-statistics';
    }
}