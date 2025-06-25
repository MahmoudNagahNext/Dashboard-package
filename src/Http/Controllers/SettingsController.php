<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Enums\TicketPriorityEnum;
use nextdev\nextdashboard\Enums\TicketStatusEnum;
use nextdev\nextdashboard\Traits\ApiResponseTrait;

class SettingsController extends Controller
{
    use ApiResponseTrait;

    public function ticketStatuses(Request $request)
    {
        $items = TicketStatusEnum::cases();

        $result = collect($items)->map(function ($item) {
            return [
                'name'   => $item->value,    // "open", "closed", etc.
                'lable' => $item->label(),  // e.g., "Open"
            ];
        });

        return $this->successResponse($result);
    }

    public function ticketPriorities(Request $request)
    {
        $items = TicketPriorityEnum::cases();

        $result = collect($items)->map(function ($item) {
            return [
                'name'   => $item->value,    // "low", "medium", etc.
                'lable' => $item->label(),  // e.g., "Low"
            ];
        });

        return $this->successResponse($result);
    }
}
