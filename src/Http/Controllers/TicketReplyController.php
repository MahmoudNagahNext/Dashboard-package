<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Http\Requests\TicketReply\StoreTicketReplyRequest;
use nextdev\nextdashboard\Http\Requests\TicketReply\UpdateTicketReplyRequest;
use nextdev\nextdashboard\Http\Resources\TicketReplyResource;
use nextdev\nextdashboard\Services\TicketReplyService;
use nextdev\nextdashboard\Traits\ApiResponseTrait;

class TicketReplyController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TicketReplyService $ticketReplyService,
    ){}

    public function store(StoreTicketReplyRequest $request): JsonResponse
    {
        try{
            $reply = $this->ticketReplyService->create($request->validated());
            return $this->successResponse(
                TicketReplyResource::make($reply),
                "Ticket Reply created successfully",
                201
            );
        } catch(\Exception $e) {
            return $this->errorResponse('',$e->getMessage(), []);
        }
    }

    public function index(Request $request, int $ticketId): JsonResponse
    {
        $replies = $this->ticketReplyService->index($ticketId);
        return $this->successResponse(
            TicketReplyResource::collection($replies),
            "Ticket Replies retrieved successfully",
            200
        );
    }

    public function update(int $ticketId, int $id, UpdateTicketReplyRequest $request): JsonResponse
    {
        try{
            $reply = $this->ticketReplyService->update($id, $request->validated());
            return $this->successResponse(
                TicketReplyResource::make($reply),
                "Ticket Reply updated successfully",
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('',$e->getMessage(), []);
        }
    }

    public function delete(int $ticketId, int $id): JsonResponse
    {
        try {
            $this->ticketReplyService->delete($id);
            return $this->successResponse(
                [],
                "Ticket Reply deleted successfully",
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('',$e->getMessage(), []);
        }
    }

    public function show(int $ticketId, int $id): JsonResponse
    {
        try {
            $reply = $this->ticketReplyService->find($id);
            return $this->successResponse(
                TicketReplyResource::make($reply),
                "Ticket Reply retrieved successfully",
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('',$e->getMessage(), []);
        }
    }
}