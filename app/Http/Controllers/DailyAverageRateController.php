<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DailyAverageRate;
use Carbon\Carbon;

class DailyAverageRateController extends Controller
{
    public function index()
    {
        $averages = DB::table('exchange_rates')
            ->select([
                DB::raw('AVG(btc_buy_rate) as avg_btc_buy_rate'),
                DB::raw('AVG(btc_sell_rate) as avg_btc_sell_rate'),
                DB::raw('AVG(rub_buy_rate) as avg_rub_buy_rate'),
                DB::raw('AVG(rub_sell_rate) as avg_rub_sell_rate'),
                DB::raw('AVG(gbp_to_rub_rate) as avg_effective_gbp_to_rub'),
                DB::raw('AVG(rub_to_gbp_rate) as avg_effective_rub_to_gbp'),
                DB::raw('AVG(spread) as avg_spread_percentage'),
            ])
            ->where('created_at', '>=', now()->subHours(23))
            ->first();
    
        $averageRate = new DailyAverageRate;
        $averageRate->datestamp = Carbon::now()->format('d/m/y');
        $averageRate->btc_buy_rate = $averages->avg_btc_buy_rate;
        $averageRate->btc_sell_rate = $averages->avg_btc_sell_rate;
        $averageRate->rub_buy_rate = $averages->avg_rub_buy_rate;
        $averageRate->rub_sell_rate = $averages->avg_rub_sell_rate;
        $averageRate->effective_gbp_to_rub = $averages->avg_effective_gbp_to_rub;
        $averageRate->effective_rub_to_gbp = $averages->avg_effective_rub_to_gbp;
        $averageRate->spread_percentage = $averages->avg_spread_percentage;
        $averageRate->save();
        
    }
    
    
}
