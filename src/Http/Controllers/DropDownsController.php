<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Models\TicketPriority;
use nextdev\nextdashboard\Models\TicketStatus;

class DropDownsController extends Controller
{
    use ApiResponseTrait;

    public function ticketStatuses()
    {
        $items = TicketStatus::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->getTranslation('name', app()->getLocale()),
            ];
        });

        return $this->successResponse($items);
    }

    public function ticketPriorities()
    {
        $items = TicketPriority::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->getTranslation('name', app()->getLocale()),
            ];
        });

        return $this->successResponse($items);
    }
}
