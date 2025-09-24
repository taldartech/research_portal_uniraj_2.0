<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicChangeResponseNotification extends Notification
{
    use Queueable;

    protected $synopsis;
    protected $response;

    /**
     * Create a new notification instance.
     */
    public function __construct($synopsis, $response)
    {
        $this->synopsis = $synopsis;
        $this->response = $response;
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
        $responseText = $this->response === 'accept' ? 'accepted' : 'rejected';
        $subject = 'Topic Change Proposal ' . ucfirst($responseText);

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('The scholar has ' . $responseText . ' your topic change proposal.')
                    ->line('Scholar: ' . $this->synopsis->scholar->user->name)
                    ->line('Original Topic: ' . $this->synopsis->proposed_topic)
                    ->line('Proposed Topic: ' . $this->synopsis->proposed_topic_change)
                    ->line('Scholar Response: ' . $this->synopsis->scholar_response_remarks)
                    ->action('View Synopsis', route('staff.synopsis.approve', $this->synopsis))
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
            'type' => 'topic_change_response',
            'synopsis_id' => $this->synopsis->id,
            'scholar_name' => $this->synopsis->scholar->user->name,
            'response' => $this->response,
            'original_topic' => $this->synopsis->proposed_topic,
            'proposed_topic' => $this->synopsis->proposed_topic_change,
            'scholar_remarks' => $this->synopsis->scholar_response_remarks,
            'responded_at' => $this->synopsis->topic_change_responded_at,
        ];
    }
}
