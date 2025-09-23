<?php

namespace App\Notifications;

use App\Models\SupervisorAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupervisorAssignmentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(SupervisorAssignment $assignment)
    {
        $this->assignment = $assignment;
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
        $scholarName = $this->assignment->scholar->user->name;
        $supervisorName = $this->assignment->supervisor->user->name;
        $assignedDate = $this->assignment->assigned_date->format('Y-m-d');

        return (new MailMessage)
                    ->subject('Supervisor Assignment Approved')
                    ->greeting('Hello ' . $scholarName . ',')
                    ->line('Your request for supervisor assignment has been approved!')
                    ->line('You have been assigned to:')
                    ->line('Supervisor: ' . $supervisorName)
                    ->line('Assigned Date: ' . $assignedDate)
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
