<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Enums\TicketPriorityEnum;
use nextdev\nextdashboard\Enums\TicketStatusEnum;

class SettingsController extends Controller
{
    public function ticketStatuses(Request $request): JsonResponse
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

        return Response::json([
            'success' => true,
            'message' => "Ticket Statuses",
            'data'    => $result
        ],200);
    }


    public function ticketPriorities(Request $request): JsonResponse
    {
        app()->setLocale($request->get('lang', app()->getLocale()));

        $items = TicketPriorityEnum::cases();

        $result = collect($items)->map(function ($item) {
            return [
                'id'   => $item->value,
                'name' => $item->label(),
            ];
        });

        return Response::json([
            'success' => true,
            'message' => "Ticket Priorities",
            'data'    => $result
        ],200);
    }

}
