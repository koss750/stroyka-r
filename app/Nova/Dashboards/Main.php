<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboards\Main as Dashboard;
use App\Nova\Metrics\DesignPurchaseStats;


class Main extends Dashboard
{

    public function name()
    {
        return 'Общая статистика';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new DesignPurchaseStats,
        ];
    }
}
