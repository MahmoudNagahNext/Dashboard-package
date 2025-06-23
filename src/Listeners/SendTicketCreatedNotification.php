<?php

namespace nextdev\nextdashboard\Listeners;

use nextdev\nextdashboard\Events\TicketCreated;
use nextdev\nextdashboard\Notifications\TicketCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use nextdev\nextdashboard\Models\Admin;

class SendTicketCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        // Notify all admins about the new ticket
        $admins = Admin::all();
        
        foreach ($admins as $admin) {
            // Don't notify the creator if they're an admin
            if ($event->ticket->creator_type === Admin::class && $event->ticket->creator_id === $admin->id) {
                continue;
            }
            
            $admin->notify(new TicketCreatedNotification($event->ticket));
        }
    }
}