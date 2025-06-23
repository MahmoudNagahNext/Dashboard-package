<?php

namespace nextdev\nextdashboard\Listeners;

use nextdev\nextdashboard\Events\TicketAssigned;
use nextdev\nextdashboard\Notifications\TicketAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketAssignedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TicketAssigned $event): void
    {
        // Notify the assigned admin
        $event->assignedTo->notify(new TicketAssignedNotification(
            $event->ticket,
            $event->assignedTo
        ));
        
        // Notify the ticket creator if they're not the assignee
        if ($event->ticket->creator_id != $event->assignedTo->id) {
            $event->ticket->creator->notify(new TicketAssignedNotification(
                $event->ticket,
                $event->assignedTo
            ));
        }
    }
}