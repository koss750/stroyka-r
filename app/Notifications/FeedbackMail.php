<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FeedbackMail extends Notification
{
    use Queueable;

    protected $name;
    protected $email;
    protected $message;
    protected $phone;

    public function __construct($name, $phone, $email, $message)
    {
        $this->name = $name;
        $this->phone = $phone ?? '';
        $this->email = $email ?? '';
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Запрос с сайта')
            ->line('Имя: ' . $this->name)
            ->line('Email: ' . $this->email)
            ->line('Телефон: ' . $this->phone)
            ->line('Сообщение: ' . $this->message);
    }
}
