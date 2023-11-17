<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormReceived extends Notification implements ShouldQueue
{
    use Queueable;
    public $formurl;
    /**
     * Create a new notification instance.
     */
    public function __construct($formUrl)
    {
        $this->formurl = $formUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->prefers_mail ? ['mail'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('workflow@example.com', 'Hello')
            ->subject('Form arrival mail.')
            ->greeting(sprintf('Hello %s!', $notifiable->name))
            ->line('You received a form that need your action')
            ->action('check the form at', $this->formurl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
