<?php

namespace nextdev\nextdashboard\Enums;

enum TicketPriorityEnum: string

{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return __('nextdashboard::dashboard.priority.' . $this->value);
    }
}
