<?php

namespace nextdev\nextdashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use nextdev\nextdashboard\Models\Ticket;
use nextdev\nextdashboard\Models\TicketReply;
use nextdev\nextdashboard\Models\Admin;

class TicketReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Ticket $ticket;
    protected TicketReply $reply;
    protected Admin $repliedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, TicketReply $reply, Admin $repliedBy)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->repliedBy = $repliedBy;
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
            ->subject("New reply to ticket #{$this->ticket->id}: {$this->ticket->title}")
            ->line("A new reply has been added to ticket #{$this->ticket->id} by {$this->repliedBy->name}.")
            ->line("Reply: {$this->reply->body}")
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
            'reply_id' => $this->reply->id,
            'reply_body' => $this->reply->body,
            'replied_by' => [
                'id' => $this->repliedBy->id,
                'name' => $this->repliedBy->name,
            ],
            'message' => "New reply to ticket #{$this->ticket->id} by {$this->repliedBy->name}",
        ];
    }
}