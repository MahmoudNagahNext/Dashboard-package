<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Auth\LoginRequest;
use nextdev\nextdashboard\Http\Requests\Auth\RegisterRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Models\TicketPriority;
use nextdev\nextdashboard\Models\TicketStatus;
use nextdev\nextdashboard\Services\AuthService;

class DropDownsController extends Controller
{
    use ApiResponseTrait;

    public function ticketStatuies()
    {
        $items = TicketStatus::all();
        return $this->successResponse($items);
    }

    public function ticketPriorities()
    {
        $items = TicketPriority::all();
        return $this->successResponse($items);
    }

}