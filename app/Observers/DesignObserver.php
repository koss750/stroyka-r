<?php

namespace App\Observers;

use App\Models\Design;
use Illuminate\Support\Facades\Artisan;
use App\Models\PortalLog;
class DesignObserver
{
    public function updated(Design $design)
    {
        // Check if 'active' was changed to true
        if ($design->wasChanged('active') && $design->active) {
            // Clear cache first
            Artisan::call('cache:clear');

            // Run commands in sequence
            $id = $design->id;
            
            // Convert images
            Artisan::call('misc:convert-images', [
                '--ids' => $id
            ]);

            // Run full indexing
            Artisan::call('app:full', [
                '--id' => $id
            ]);

            // Fix design details
            Artisan::call('misc:fix-design-details', [
                'id' => $id
            ]);

            PortalLog::create([
                'loggable_type' => get_class($design),
                'loggable_id' => $design->id,
                'action' => 'Автоматическая индексация',
                'action_type' => 'auto-index',
                'user_id' => auth()->id() ?? 7,
            ]);
        }
    }
}