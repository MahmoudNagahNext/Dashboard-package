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

class TicketController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TicketService $ticketService
    ){}

    public function index()
    {
        $this->authorize('ticket.view');

        $tickets = $this->ticketService->paginate(['creator','assignee','status','priority','category', 'media']);
        return $this->paginatedCollectionResponse($tickets,'Tickets Paginated', [], TicketResource::class);
    }

    public function store(TicketStoreRequest $request)
    {
        $this->authorize('ticket.create');

        try{
            $data = $request->validated();
            $data['attachments'] = $request->file('attachments',[]);

            $dto = TicketDTO::fromRequest($data);
            $ticket = $this->ticketService->create($dto);
            return $this->createdResponse(TicketResource::make($ticket));
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        $this->authorize('ticket.view');

        $ticket = $this->ticketService->find($id,['creator','assignee','status','priority','category', 'media']);
        return $this->successResponse(TicketResource::make($ticket));
    }

    public function update(TicketUpdateRequest $request,int $id)
    {
        $this->authorize('ticket.update'); 

        try{            
            $data = $request->validated();
            $data['attachments'] = $request->file('attachments',[]);

            $dto = TicketDTO::fromRequest($data);
            $this->ticketService->update($dto, $id);

            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        $this->authorize('ticket.delete');
        
        try{
            $this->ticketService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}