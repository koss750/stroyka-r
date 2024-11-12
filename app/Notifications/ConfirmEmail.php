<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ConfirmEmail extends Notification
{
    use Queueable;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $notifiable->getKey()]
        );

        return (new MailMessage)
            ->subject('Подтвердите ваш email адрес')
            ->line('Пожалуйста, нажмите на кнопку ниже, чтобы подтвердить ваш email адрес.')
            ->action('Подтвердить email адрес', $url)
            ->line('Если вы не создавали аккаунт, никаких дальнейших действий не требуется.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}