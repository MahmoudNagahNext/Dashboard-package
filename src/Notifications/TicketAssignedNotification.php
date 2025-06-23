<?php

namespace nextdev\nextdashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use nextdev\nextdashboard\Models\Ticket;
use nextdev\nextdashboard\Models\Admin;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Ticket $ticket;
    protected Admin $assignedTo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, Admin $assignedTo)
    {
        $this->ticket = $ticket;
        $this->assignedTo = $assignedTo;
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
            ->subject("Ticket #{$this->ticket->id} Assigned")
            ->line("Ticket #{$this->ticket->id}: {$this->ticket->title} has been assigned to {$this->assignedTo->name}.")
            ->action('View Ticket', url("/dashboard/tickets/{$this->ticket->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'assigned_to' => [
                'id' => $this->assignedTo->id,
                'name' => $this->assignedTo->name,
            ],
            'message' => "Ticket #{$this->ticket->id} assigned to {$this->assignedTo->name}",
        ];
    }
}