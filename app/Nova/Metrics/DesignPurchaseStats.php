<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Design;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Table;
use Laravel\Nova\Metrics\MetricTableRow;
use Illuminate\Http\Request;
class DesignPurchaseStats extends Table
{
    public function name()
    {
        return 'Статистика покупок по проектам';
    }

    public function calculate(NovaRequest $request)
    {
        $stats = Design::select('designs.id', 'designs.title', 'designs.view_count')
            ->selectRaw('COUNT(projects.id) as purchases')
            ->selectRaw('SUM(projects.payment_amount) as revenue')
            ->leftJoin('projects', 'designs.id', '=', 'projects.design_id')
            ->groupBy('designs.id', 'designs.title', 'designs.view_count')
            ->orderByDesc('purchases')
            ->get();

        $totalDesigns = $stats->count();
        $totalPurchases = $stats->sum('purchases');
        $totalRevenue = $stats->sum('revenue');

        return [
            MetricTableRow::make()
                ->title('totalDesigns')
                ->subtitle($totalDesigns),
            MetricTableRow::make()
                ->title('totalPurchases')
                ->subtitle($totalPurchases),
            MetricTableRow::make()
                ->title('totalRevenue')
                ->subtitle($totalRevenue),
        ];
    }

    public function uriKey()
    {
        return 'design-purchase-stats';
    }
}