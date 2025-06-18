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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TicketController extends Controller
{
    use ApiResponseTrait, AuthorizesRequests;

    public function __construct(
        protected TicketService $ticketService
    ){}

    public function index()
    {
        try{
            $this->authorize('ticket.view');

            $tickets = $this->ticketService->paginate(['creator','assignee','status','priority','category', 'media']);
            return $this->paginatedCollectionResponse($tickets,'Tickets Paginated', [], TicketResource::class);
        } catch (\Exception $e){
            return $this->handleException($e);
        }
    }

    public function store(TicketStoreRequest $request)
    {
        try{
            // $this->authorize('ticket.create');

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
        try{
            // $this->authorize('ticket.view');
            auth()->user()->can('ticket.view');

            $ticket = $this->ticketService->find($id,['creator','assignee','status','priority','category', 'media']);
            return $this->successResponse(TicketResource::make($ticket));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(TicketUpdateRequest $request,int $id)
    {
        try{            
            // $this->authorize('ticket.update');

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
        try{
            // $this->authorize('ticket.delete');
     
            $this->ticketService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}