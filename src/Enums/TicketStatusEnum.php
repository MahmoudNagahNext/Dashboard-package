<?php

namespace nextdev\nextdashboard\Enums;

enum TicketStatusEnum: string

{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case REJECTED = 'rejected';


    public function label(): string
    {
        return __('nextdashboard::dashboard.status.' . $this->value);
    }
}
