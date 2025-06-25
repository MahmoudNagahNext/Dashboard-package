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
        // Set the language from the request
        app()->setLocale($request->get('lang', app()->getLocale()));

        $items = TicketStatusEnum::cases();

        $result = collect($items)->map(function ($item) {
            return [
                'id'   => $item->value,
                'name' => $item->label(),
            ];
        });

        return $this->successResponse($result);
    }


    public function ticketPriorities(Request $request)
    {
        app()->setLocale($request->get('lang', app()->getLocale()));

        $items = TicketPriorityEnum::cases();

        $result = collect($items)->map(function ($item) {
            return [
                'id'   => $item->value,
                'name' => $item->label(),
            ];
        });

        return $this->successResponse($result);
    }

}
