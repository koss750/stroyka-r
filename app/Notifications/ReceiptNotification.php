<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
class ReceiptNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $design;
    protected $user_email;
    protected $override;
    public function __construct($project, $design, $user_email, $override = false)
    {
        $this->project = $project;
        $this->design = $design;
        $this->user_email = $user_email;
        $this->override = $override;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $view = View::make('emails.receipt', [
            'project' => $this->project,
            'design' => $this->design,
            'user_email' => $this->user_email,
        ]);

        $html = $view->render();

        // check if such notification was already sent earlier
        if (!$this->override) {
            /* NOT WORKING
            $notification =  DB::table('notifications')
                ->where('type', 'App\Notifications\ReceiptNotification')
                ->where('data->project_id', $this->project->id)
                ->first();
            if ($notification) {
                return;
            }
            */
        }

        return (new MailMessage)
            ->subject('Кассовый чек')
            ->line('Пожалуйста, найдите ваш кассовый чек ниже.')
            ->view('emails.receipt', [
                'project' => $this->project,
                'design' => $this->design,
                'user_email' => $this->user_email,
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'design_id' => $this->design->id,
            'amount' => $this->project->payment_amount,
            // Add any other data you want to store
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
