<?php

namespace App\Notifications;

use App\Models\CourseworkExemption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseworkExemptionApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $courseworkExemption;

    /**
     * Create a new notification instance.
     */
    public function __construct(CourseworkExemption $courseworkExemption)
    {
        $this->courseworkExemption = $courseworkExemption;
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
        $scholarName = $this->courseworkExemption->scholar->user->name;
        $reason = $this->courseworkExemption->reason;
        $approvalDate = $this->courseworkExemption->dean_approval_date ? $this->courseworkExemption->dean_approval_date->format('Y-m-d') : 'N/A';

        return (new MailMessage)
                    ->subject('Coursework Exemption Approved')
                    ->greeting('Hello ' . $scholarName . ',')
                    ->line('Your request for coursework exemption has been approved by the Dean!')
                    ->line('Reason: ' . $reason)
                    ->line('Approval Date: ' . $approvalDate)
                    ->action('View Your Dashboard', url('/scholar/dashboard'))
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
