<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function __construct(
        protected string $temporaryPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to KSTL Lab — Your Account Details')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your account has been created by the lab director.')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Temporary Password:** ' . $this->temporaryPassword)
            ->action('Log In Now', url('/admin/login'))
            ->line('Please log in and change your password immediately.')
            ->line('If you have any issues, contact the lab reception.');
    }
}