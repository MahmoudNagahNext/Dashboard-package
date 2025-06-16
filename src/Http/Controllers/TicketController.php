<?php

namespace nextdev\nextdashboard\Http\Controllers;

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
        $tickets = $this->ticketService->paginate(['creator','assignee','status','priority','category']);
        return $this->paginatedCollectionResponse($tickets,'Tickets Paginated', [], TicketResource::class);
    }

    public function store(TicketStoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $data = array_merge(
                $validated,
                ['attachments' => $request->file('attachments')]
            );

            $dto = TicketDTO::fromRequest($data);
            $ticket = $this->ticketService->create($dto);
            return $this->createdResponse(TicketResource::make($ticket));
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        return $this->successResponse(TicketResource::make($this->ticketService->find($id,['creator','assignee','status','priority','category'])));
    }

    public function update(TicketUpdateRequest $request,int $id)
    {
        try{
            $this->ticketService->update($request->validated(), $id);
            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->ticketService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}