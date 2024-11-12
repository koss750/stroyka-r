<?php

namespace BorodinServices\DesignFiles;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use BorodinServices\DesignFiles\Http\Middleware\Authorize;
use App\Http\Controllers\Nova\DesignFilesController;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::get('/design-files/{designId}', [DesignFilesController::class, 'listFiles']);
        Route::get('/design-files/download/{fileName}', [DesignFilesController::class, 'downloadFile']);
        Route::delete('/design-files/{fileName}', [DesignFilesController::class, 'deleteFile']);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authenticate::class, Authorize::class], 'design-files')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/design-files')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
