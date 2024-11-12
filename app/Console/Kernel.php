<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\PersistDesignViews;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {    
        // Keep track of design views
        $schedule->job(new PersistDesignViews())->daily();

        // Clean up old orders
        //$schedule->command('orders:cleanup')->daily();
        $schedule->command('blog:publish-oldest')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        Commands\DesignDetailsCommand::class,
        Commands\ImageConversionCommand::class,
    ];
}
