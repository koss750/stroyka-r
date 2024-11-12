<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\ReceiptNotification;
use App\Models\User;
use App\Models\Project;
use App\Models\Design;

class ResendNotification extends Command
{
    protected $signature = 'notification:resend {id}';
    protected $description = 'Resend a notification by its ID';

    public function handle()
    {
        $notificationId = $this->argument('id');
        
        $notification = DatabaseNotification::findOrFail($notificationId);
        $user = User::findOrFail($notification->notifiable_id);
        $project = Project::findOrFail($notification->data['project_id']);
        $design = Design::findOrFail($notification->data['design_id']);

        // Recreate the notification
        $receiptNotification = new ReceiptNotification(
            $project,
            $design,
            $user->email
        );

        // Resend the notification
        $user->notify($receiptNotification);

        $this->info("Notification {$notificationId} has been resent.");
    }
}