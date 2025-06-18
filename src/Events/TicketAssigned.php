<?php 
namespace nextdev\nextdashboard\Events;

use Illuminate\Queue\SerializesModels;
use nextdev\nextdashboard\Models\Ticket;
use nextdev\nextdashboard\Models\Admin;

class TicketAssigned
{
    use SerializesModels;

    public Ticket $ticket;
    public Admin $assignedTo;

    public function __construct(Ticket $ticket, Admin $assignedTo)
    {
        $this->ticket = $ticket;
        $this->assignedTo = $assignedTo;
    }
}
