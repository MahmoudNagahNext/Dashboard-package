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
use nextdev\nextdashboard\Events\TicketCreated;
use nextdev\nextdashboard\Http\Requests\Ticket\BulkDeleteRequest;


class TicketController extends Controller
{
    use ApiResponseTrait, AuthorizesRequests;

    public function __construct(
        protected TicketService $ticketService
    ){}

    public function index()
    {
        try{
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.view')) {
                return $this->errorResponse('Unauthorized.', 403);
            }

            $tickets = $this->ticketService->paginate(['creator','assignee','status','priority','category', 'media']);
            return $this->paginatedCollectionResponse($tickets,'Tickets Paginated', [], TicketResource::class);
        } catch (\Exception $e){
            return $this->handleException($e);
        }
    }

    public function store(TicketStoreRequest $request)
    {
        try{
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.create')) {
                return $this->errorResponse('Unauthorized.', 403);
            }

            $data = $request->validated();
            $data['attachments'] = $request->file('attachments',[]);

            $dto = TicketDTO::fromRequest($data);
            $ticket = $this->ticketService->create($dto);

            event(new TicketCreated($ticket));
            return $this->createdResponse(TicketResource::make($ticket));
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try{
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.view')) {
                return $this->errorResponse('Unauthorized.', 403);
            }

            $ticket = $this->ticketService->find($id,['creator','assignee','status','priority','category', 'media']);
            return $this->successResponse(TicketResource::make($ticket));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(TicketUpdateRequest $request,int $id)
    {
        try{            
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.update')) {
                return $this->errorResponse('Unauthorized.', 403);
            }

            $data = $request->validated();
            $data['attachments'] = $request->file('attachments',[]);

            $dto = TicketDTO::fromRequest($data);
            $this->ticketService->update($dto, $id);
            
            // event(new TicketAssigned($ticket, $assignedAdmin));
            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.delete')) {
                return $this->errorResponse('Unauthorized.', 403);
            }
     
            $this->ticketService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        try{
            if (!auth()->guard('admin')->user()->hasPermissionTo('ticket.delete')) {
                return $this->errorResponse('Unauthorized.', 403);
            }
            
            $this->ticketService->bulkDelete($request->validated()['ids']);
            return $this->deletedResponse('Tickets deleted successfully');
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}