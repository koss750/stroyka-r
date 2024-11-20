<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Сброс пароля')
            ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сбросить пароль', route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->email
            ]))
            ->line('Срок действия ссылки для сброса пароля истечет через 60 минут.')
            ->line('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.');
    }
}