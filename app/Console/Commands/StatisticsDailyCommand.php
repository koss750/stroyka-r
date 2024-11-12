<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DailyAverageRateController;

class StatisticsDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new DailyAverageRateController;
        $controller->index();
    }
    
}
