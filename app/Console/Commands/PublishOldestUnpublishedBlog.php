<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost as Blog;
use Carbon\Carbon;

class PublishOldestUnpublishedBlog extends Command
{
    protected $signature = 'blog:publish-oldest';

    protected $description = 'Publish the oldest unpublished blog if no blog has been updated in the last 72 hours';

    public function handle()
    {
        $latestPublishedBlog = Blog::where('is_published', true)
                                   ->latest('updated_at')
                                   ->first();

        if (!$latestPublishedBlog || $latestPublishedBlog->updated_at->diffInHours(now()) > 72) {
            $oldestUnpublishedBlog = Blog::where('is_published', false)
                                         ->oldest('created_at')
                                         ->first();

            if ($oldestUnpublishedBlog) {
                $oldestUnpublishedBlog->update([
                    'is_published' => true,
                    'updated_at' => now(),
                ]);

                $this->info("Published blog: {$oldestUnpublishedBlog->title}");
            } else {
                $this->info('No unpublished blogs available to publish.');
            }
        } else {
            $this->info('A blog has been updated within the last 72 hours. No action taken.');
        }
    }
}