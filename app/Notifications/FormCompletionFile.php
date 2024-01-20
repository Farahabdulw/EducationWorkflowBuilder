<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class FormCompletionFile extends Notification implements ShouldQueue
{
    use Queueable;
    public $workflow;
    public $message;
    public $workflowUrl;
    /**
     * Create a new notification instance.
     */
    public function __construct($workflow, $message)
    {
        $this->workflow = $workflow;
        $this->message = $message;
        $this->workflowUrl = url("download/form/".Crypt::encryptString($workflow->form->id));
        
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
            ->subject('Worlflow Completion mail.')
            ->greeting('Hello ! Mr.'. $notifiable->last_name)
            ->line($this->message)
            ->line('The form No.'.$this->workflow->form->id)
            ->action("$notifiable->first_name Form", $this->workflowUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'Sname' => 'System',
            'body' => $this->message,
            'header' => "Download Form",
            'url' => $this->workflowUrl,
        ];
    }
}
