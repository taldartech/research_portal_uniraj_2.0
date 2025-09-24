<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicChangeProposalNotification extends Notification
{
    use Queueable;

    protected $synopsis;

    /**
     * Create a new notification instance.
     */
    public function __construct($synopsis)
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Research Topic Change Proposal')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your supervisor has proposed a change to your research topic.')
                    ->line('Current Topic: ' . $this->synopsis->proposed_topic)
                    ->line('Proposed New Topic: ' . $this->synopsis->proposed_topic_change)
                    ->line('Reason: ' . $this->synopsis->topic_change_reason)
                    ->action('Review Proposal', route('scholar.synopsis.topic-change-response', $this->synopsis))
                    ->line('Please review the proposal and respond accordingly.')
                    ->line('Thank you for using our research portal!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'topic_change_proposal',
            'synopsis_id' => $this->synopsis->id,
            'current_topic' => $this->synopsis->proposed_topic,
            'proposed_topic' => $this->synopsis->proposed_topic_change,
            'reason' => $this->synopsis->topic_change_reason,
            'supervisor_name' => $this->synopsis->topicChangeProposedBy->name ?? 'Unknown',
            'proposed_at' => $this->synopsis->topic_change_proposed_at,
        ];
    }
}
