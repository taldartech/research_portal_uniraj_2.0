<?php

namespace App\Notifications;

use App\Models\Synopsis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SynopsisRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $synopsis;

    /**
     * Create a new notification instance.
     */
    public function __construct(Synopsis $synopsis)
    {
        $this->synopsis = $synopsis;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $scholarName = $this->synopsis->scholar->user->name;
        $supervisorName = $this->synopsis->rac->supervisor->user->name;
        $proposedTopic = $this->synopsis->proposed_topic;
        $remarks = $this->synopsis->remarks ?? 'No specific remarks provided.';

        return (new MailMessage)
                    ->subject('Synopsis Rejected')
                    ->greeting('Hello ' . $scholarName . ',')
                    ->line('Your proposed synopsis has been rejected by the DRC.')
                    ->line('Proposed Topic: ' . $proposedTopic)
                    ->line('Supervisor: ' . $supervisorName)
                    ->line('Remarks: ' . $remarks)
                    ->action('View Your Dashboard', url('/scholar/dashboard'))
                    ->line('Please review the feedback and resubmit if necessary.')
                    ->line('Thank you for using our Research Project Workflow Automation Portal!');
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
