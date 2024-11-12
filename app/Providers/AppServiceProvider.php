<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use App\Services\CaptionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	    Schema::defaultStringLength(191);
        Blade::directive('caption', function ($expression) {
            return "<?php echo app('" . CaptionService::class . "')->get($expression); ?>";
        });
    }
}
