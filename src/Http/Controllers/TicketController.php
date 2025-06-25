<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\DTOs\TicketDTO;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Ticket\TicketStoreRequest;
use nextdev\nextdashboard\Http\Requests\Ticket\TicketUpdateRequest;
use nextdev\nextdashboard\Http\Resources\TicketResource;
use nextdev\nextdashboard\Services\TicketService;
use nextdev\nextdashboard\Http\Requests\Ticket\BulkDeleteRequest;


class TicketController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TicketService $ticketService
    ) {
        $this->middleware('can:ticket.view')->only(['index', 'show']);
        $this->middleware('can:ticket.create')->only('store');
        $this->middleware('can:ticket.update')->only('update');
        $this->middleware('can:ticket.delete')->only('destroy');
    }

    public function index(Request $request)
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

        return $this->paginatedCollectionResponse(
            $tickets,
            'Tickets Paginated',
            [],
            TicketResource::class
        );
    }


    public function store(TicketStoreRequest $request)
    {
        $data = $request->validated();
        $data['attachments'] = $request->file('attachments', []);

        $ticket = $this->ticketService->create($data);

        // event(new TicketCreated($ticket));
        return $this->createdResponse(TicketResource::make($ticket));
    }

    public function show(int $id)
    {
        $ticket = $this->ticketService->find($id, ['creator', 'assignee', 'category', 'media']);
        return $this->successResponse(TicketResource::make($ticket));
    }

    public function update(TicketUpdateRequest $request, int $id)
    {
        $data = $request->validated();
        $data['attachments'] = $request->file('attachments', []);

        $this->ticketService->update($data, $id);

        // event(new TicketAssigned($ticket, $assignedAdmin));
        return $this->updatedResponse();
    }

    public function destroy(int $id)
    {
        $this->ticketService->delete($id);
        return $this->deletedResponse();
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        $this->ticketService->bulkDelete($request->validated()['ids']);
        return $this->deletedResponse('Tickets deleted successfully');
    }
}
