<?php

namespace nextdev\nextdashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use nextdev\nextdashboard\Models\Ticket;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Ticket $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
            ->subject("New Ticket Created: {$this->ticket->title}")
            ->line("A new ticket has been created: {$this->ticket->title}")
            ->line("Description: {$this->ticket->description}")
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
            'ticket_description' => $this->ticket->description,
            'message' => "New ticket created: {$this->ticket->title}",
        ];
    }
}