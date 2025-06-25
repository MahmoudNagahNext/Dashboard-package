<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\Ticket\AddAttachmentsRequest;
use nextdev\nextdashboard\Http\Requests\Ticket\TicketStoreRequest;
use nextdev\nextdashboard\Http\Requests\Ticket\TicketUpdateRequest;
use nextdev\nextdashboard\Http\Resources\TicketResource;
use nextdev\nextdashboard\Services\TicketService;
use nextdev\nextdashboard\Http\Requests\Ticket\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Requests\Ticket\DeleteAttachmentsRequest;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {
        $this->middleware('can:ticket.view')->only(['index', 'show']);
        $this->middleware('can:ticket.create')->only('store');
        $this->middleware('can:ticket.update')->only('update');
        $this->middleware('can:ticket.delete')->only('destroy');
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $with = ['creator', 'assignee', 'category', 'media'];
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');

        $filters = $request->only([
            'status', 
            'priority', 
            'creator_id', 
            'assignee_id', 
            'category_id'
        ]);

        $tickets = $this->ticketService->paginate($search, $with, $perPage, $page, $sortBy, $sortDirection, $filters);

        return Response::json([
            'success' => true,
            'message' => "Tickets Paginated",
            'data'    => TicketResource::collection($tickets),
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
            ]
        ],200);
    }


    public function store(TicketStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['attachments'] = $request->file('attachments', []);

        $ticket = $this->ticketService->create($data);

        // event(new TicketCreated($ticket));
        return Response::json([
            'success' => true,
            'message' => "Ticket Created",
            'data'    => TicketResource::make($ticket)
        ],201);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->find($id, ['creator', 'assignee', 'category', 'media']);
        return Response::json([
            'success' => true,
            'message' => "Ticket Showed",
            'data'    => TicketResource::make($ticket)
        ],200);
    }

    public function update(TicketUpdateRequest $request, $id): JsonResponse
    {
        $this->ticketService->updateTicket($request->validated(), $id);
        // event(new TicketAssigned($ticket, $assignedAdmin));
        return Response::json([
            'success' => true,
            'message' => 'Ticket updated successfully',
            'data'    => []
        ]);
    }

    public function addAttachments(AddAttachmentsRequest $request, $id): JsonResponse
    {
        $this->ticketService->addAttachments($id, $request->file('attachments'));
        // event(new TicketAssigned($ticket, $assignedAdmin));
        return Response::json([
            'success' => true,
            'message' => 'Attachments added successfully',
            'data'    => []
        ]);
    }

    public function deleteAttachments(DeleteAttachmentsRequest $request, $id): JsonResponse
    {
        $this->ticketService->deleteAttachments($id, $request->input('media_ids'));
        // event(new TicketAssigned($ticket, $assignedAdmin));
        return Response::json([
            'success' => true,
            'message' => 'Attachments deleted successfully',
            'data' => []
        ]);
    }


    public function destroy(int $id): JsonResponse
    {
        $this->ticketService->delete($id);
        return Response::json([
            'success' => true,
            'message' => 'Ticket deleted successfully',
            'data' => []
        ]);
    }

    public function bulkDelete(BulkDeleteRequest $request): JsonResponse
    {
        $this->ticketService->bulkDelete($request->validated()['ids']);
        return Response::json([
            'success' => true,
            'message' => 'Tickets deleted successfully',
            'data' => []
        ]);
    }
}
