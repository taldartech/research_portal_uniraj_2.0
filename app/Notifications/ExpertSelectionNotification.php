<?php

namespace App\Notifications;

use App\Models\ThesisSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpertSelectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $thesis;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(ThesisSubmission $thesis, string $role = 'dr')
    {
        $this->thesis = $thesis;
        $this->role = $role;
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
        $thesis = $this->thesis->load(['scholar.user', 'thesisEvaluation.expert']);
        $scholarName = $thesis->scholar->user->name ?? 'N/A';
        $thesisTitle = $thesis->title ?? 'N/A';
        
        $selectedExperts = $thesis->thesisEvaluation->map(function($evaluation) {
            return [
                'name' => $evaluation->expert->name ?? 'N/A',
                'email' => $evaluation->expert->email ?? 'N/A',
                'priority' => $evaluation->priority_order ?? 'N/A',
            ];
        })->sortBy('priority');

        $mail = (new MailMessage)
            ->subject('Expert Selection for Thesis Evaluation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('HVC has selected 4 experts for thesis evaluation.')
            ->line('**Thesis Details:**')
            ->line('Scholar: ' . $scholarName)
            ->line('Title: ' . $thesisTitle);

        foreach ($selectedExperts as $expert) {
            $mail->line("**Priority {$expert['priority']}:** {$expert['name']} ({$expert['email']})");
        }

        // Set the appropriate route based on role
        $routeName = $this->role === 'da' ? 'da.thesis.expert_details' : 'dr.thesis.expert_details';
        $mail->action('View Expert Details', route($routeName, $thesis->id))
            ->line('Please review the expert details and proceed with the next steps.')
            ->line('Thank you for using our Research Project Workflow Automation Portal!');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'thesis_id' => $this->thesis->id,
            'thesis_title' => $this->thesis->title,
        ];
    }
}
