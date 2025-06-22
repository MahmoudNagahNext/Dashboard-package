<?php

namespace nextdev\nextdashboard\Observers;

use Illuminate\Support\Facades\Auth;
use nextdev\nextdashboard\Models\Ticket;

class TicketObserver
{
    public function created(Ticket $ticket)
    {
        activity('ticket')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($ticket)
            ->log("Ticket created: #{$ticket->id}");
    }

    public function updated(Ticket $ticket)
    {
        activity('ticket')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($ticket)
            ->withProperties([
                'old' => $ticket->getOriginal(),
                'new' => $ticket->getChanges(),
            ])
            ->log("Ticket updated: #{$ticket->id}");
    }

    public function deleted(Ticket $ticket)
    {
        activity('ticket')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($ticket)
            ->log("Ticket deleted: #{$ticket->id}");
    }
}
