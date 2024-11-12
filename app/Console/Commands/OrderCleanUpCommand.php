<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrderCleanUpCommand extends Command
{
    protected $signature = 'misc:cleanup-orders';
    protected $description = 'Clean up old orders and files';

    public function handle()
    {
        $this->cleanupOldProjects();
        $this->cleanupOrphanedFiles();
        $this->cleanupOldFiles();
    }

    private function cleanupOldProjects()
    {
        $cutoffTime = Carbon::now()->subHours(12);

        $deletedCount = Project::where(function ($query) use ($cutoffTime) {
            $query->whereNull('filepath')
                  ->orWhere('created_at', '<', $cutoffTime);
        })->delete();

        $this->info("Deleted {$deletedCount} old or incomplete projects.");
    }

    private function cleanupOrphanedFiles()
    {
        $files = Storage::disk('smeta')->files('orders');
        $projectFilepaths = Project::whereNotNull('filepath')->pluck('filepath')->map(function ($filepath) {
            return basename($filepath);
        })->toArray();

        $orphanedFiles = array_diff($files, $projectFilepaths);
        foreach ($orphanedFiles as $file) {
            Storage::disk('public')->delete($file);
        }

        $this->info("Deleted " . count($orphanedFiles) . " orphaned files.");
    }

    private function cleanupOldFiles()
    {
        $cutoffDate = Carbon::now()->subDays(30);
        $expiredProjects = Project::whereNotNull('filepath')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        foreach ($expiredProjects as $project) {
            Storage::disk('public')->delete($project->filepath);
            $project->update(['filepath' => 'https://стройка.com/expired']);
        }

        $this->info("Updated " . $expiredProjects->count() . " projects with expired files.");
    }
}