<?php

namespace nextdev\nextdashboard\Listeners;

use nextdev\nextdashboard\Events\TicketReplied;
use nextdev\nextdashboard\Notifications\TicketReplyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketReplyNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TicketReplied $event): void
    {
        // Notify the ticket creator if they're not the one who replied
        if ($event->ticket->creator_id != $event->repliedBy->id) {
            $event->ticket->creator->notify(new TicketReplyNotification(
                $event->ticket,
                $event->reply,
                $event->repliedBy
            ));
        }

        // Notify the ticket assignee if they're not the one who replied
        if ($event->ticket->assignee_id && $event->ticket->assignee_id != $event->repliedBy->id) {
            $event->ticket->assignee->notify(new TicketReplyNotification(
                $event->ticket,
                $event->reply,
                $event->repliedBy
            ));
        }
    }
}