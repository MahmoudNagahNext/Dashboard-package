<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\TicketReply\StoreTicketReplyRequest;
use nextdev\nextdashboard\Http\Requests\TicketReply\UpdateTicketReplyRequest;
use nextdev\nextdashboard\Http\Resources\TicketReplyResource;
use nextdev\nextdashboard\Services\TicketReplyService;

class TicketReplyController extends Controller
{
    public function __construct(
        protected TicketReplyService $ticketReplyService,
    ) {}

    public function store(StoreTicketReplyRequest $request): JsonResponse
    {
        $reply = $this->ticketReplyService->create($request->validated());

        // Get the ticket and admin for the event
        $ticket = $reply->ticket;
        $admin = $reply->admin;

        // Dispatch the event
        // event(new TicketReplied($ticket, $reply, $admin));
        return Response::json([
            'success' => true,
            'message' => 'Ticket Reply created successfully',
            'data' => TicketReplyResource::make($reply)
        ], 201);
    }

    public function index(Request $request, int $ticketId): JsonResponse
    {
        $replies = $this->ticketReplyService->index($ticketId);
        return Response::json([
            'success' => true,
            'message' => 'Ticket Replies retrieved successfully',
            'data' => TicketReplyResource::collection($replies)
        ], 200);
    }

    public function update(int $ticketId, int $id, UpdateTicketReplyRequest $request): JsonResponse
    {
        $reply = $this->ticketReplyService->update($id, $request->validated());
        return Response::json([
            'success' => true,
            'message' => 'Ticket Reply updated successfully',
            'data' => []
        ], 200);
    }

    public function delete(int $ticketId, int $id): JsonResponse
    {
        $this->ticketReplyService->delete($id);
        return Response::json([
            'success' => true,
            'message' => 'Ticket Reply deleted successfully',
            'data' => []
        ], 200);
    }

    public function show(int $ticketId, int $id): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => 'Ticket Reply retrieved successfully',
            'data' => TicketReplyResource::make($$this->ticketReplyService->find($id))
        ], 200);
    }
}
