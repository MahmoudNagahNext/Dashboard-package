<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Models\TicketPriority;
use nextdev\nextdashboard\Models\TicketStatus;

class DropDownsController extends Controller
{
    use ApiResponseTrait;

    public function ticketStatuses(Request $request)
    {
        $lang = $request->get('lang', app()->getLocale()); // or use $request->header('Accept-Language')
        
        $items = TicketStatus::all()->map(function ($item) use ($lang) {
            return [
                'id' => $item->id,
                'name' => $item->getTranslation('name', $lang),
            ];
        });

        return $this->successResponse($items);
    }

    public function ticketPriorities(Request $request)
    {
        $lang = $request->get('lang', app()->getLocale()); // or use $request->header('Accept-Language')

        $items = TicketPriority::all()->map(function ($item) use ($lang) {
            return [
                'id' => $item->id,
                'name' => $item->getTranslation('name', $lang),
            ];
        });

        return $this->successResponse($items);
    }
}
