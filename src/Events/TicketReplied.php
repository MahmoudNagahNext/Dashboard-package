<?php 
namespace nextdev\nextdashboard\Events;

use Illuminate\Queue\SerializesModels;
use nextdev\nextdashboard\Models\Ticket;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\TicketReply;

class TicketReplied
{
    use SerializesModels;

    public Ticket $ticket;
    public TicketReply $reply;
    public Admin $repliedBy;

    public function __construct(Ticket $ticket, TicketReply $reply, Admin $repliedBy)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->repliedBy = $repliedBy;
    }
}