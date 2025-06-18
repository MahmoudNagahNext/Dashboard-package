<?php 
namespace nextdev\nextdashboard\Events;

use Illuminate\Queue\SerializesModels;
use nextdev\nextdashboard\Models\Ticket;

class TicketCreated
{
    use SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}
