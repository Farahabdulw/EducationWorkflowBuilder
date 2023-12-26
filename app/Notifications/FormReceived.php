<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class FormReceived extends Notification implements ShouldQueue
{
    use Queueable;
    public $sender_id;
    public $message;
    public $formUrl;
    /**
     * Create a new notification instance.
     */
    public function __construct($sender_id, $message, $form_id, $step_id)
    {
        $this->sender_id = $sender_id;
        $this->message = $message;
        $encryptedStepId = Crypt::encryptString($step_id);
        $this->formUrl = route('review-form', ['id' => $form_id, 'step_id' => $encryptedStepId]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('workflow@example.com')
            ->subject('Form arrival mail.')
            ->greeting(sprintf('Hello !', $notifiable->first_name))
            ->line($this->message)
            ->line('check the form at')
            ->action("Review Form", $this->formUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sender_id' => $this->sender_id,
            'Sname' => $notifiable->first_name . " " . $notifiable->last_name,
            'url' => $this->formUrl,
            'body' => $this->message,
            'header' => "Form Received",
        ];
    }
}
