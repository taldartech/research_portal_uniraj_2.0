<?php

namespace App\Notifications;

use App\Models\Synopsis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SynopsisApproved extends Notification implements ShouldQueue
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

        return (new MailMessage)
                    ->subject('Synopsis Approved')
                    ->greeting('Hello ' . $scholarName . ',')
                    ->line('Your proposed synopsis has been approved by the DRC!')
                    ->line('Proposed Topic: ' . $proposedTopic)
                    ->line('Supervisor: ' . $supervisorName)
                    ->action('View Your Synopsis', url('/scholar/registration/phd_form'))
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
