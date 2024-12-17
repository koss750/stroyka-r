<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Models\PortalLog;

class IndexDesign extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Индексировать';
    public $timeout = 600;

    public function retryUntil()
    {
        return Carbon::now()->addMinutes(10);
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            // Log the start of indexing
            PortalLog::create([
                'loggable_type' => get_class($model),
                'loggable_id' => $model->id,
                'action' => 'Индексация начата',
                'action_type' => 'index',
                'user_id' => auth()->id(),
                'details' => [
                    'design_id' => $model->id,
                    'started_at' => now()->toDateTimeString()
                ]
            ]);

            try {
                // Clear cache first
                Artisan::call('cache:clear');

                // Convert images
                Artisan::call('misc:convert-images', [
                    '--ids' => $model->id
                ]);

                // Run full indexing
                Artisan::call('app:full', [
                    '--id' => $model->id
                ]);

                // Fix design details
                Artisan::call('misc:fix-design-details', [
                    'id' => $model->id
                ]);

                // Log successful completion
                PortalLog::create([
                    'loggable_type' => get_class($model),
                    'loggable_id' => $model->id,
                    'action' => 'Индексация завершена',
                    'action_type' => 'index',
                    'user_id' => auth()->id(),
                    'details' => [
                        'design_id' => $model->id,
                        'completed_at' => now()->toDateTimeString(),
                        'status' => 'success'
                    ]
                ]);

            } catch (\Exception $e) {
                // Log error if something fails
                PortalLog::create([
                    'loggable_type' => get_class($model),
                    'loggable_id' => $model->id,
                    'action' => 'Ошибка индексации',
                    'action_type' => 'index_error',
                    'user_id' => auth()->id(),
                    'details' => [
                        'design_id' => $model->id,
                        'error' => $e->getMessage(),
                        'failed_at' => now()->toDateTimeString()
                    ]
                ]);

                throw $e;
            }
        }

        return Action::message('Индексация завершена');
    }
}