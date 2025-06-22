<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\TicketCategory\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Requests\TicketCategory\TicketCategoryStoreRequest;
use nextdev\nextdashboard\Http\Requests\TicketCategory\TicketCategoryUpdateRequest;
use nextdev\nextdashboard\Services\TicketCategoriesService;

class TicketCategoriesController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TicketCategoriesService $service
    ){}

    public function index()
    {
        $items = $this->service->paginate();
        return $this->paginatedResponse($items);
    }

    public function store(TicketCategoryStoreRequest $request)
    {
        try{
            $item = $this->service->create($request->validated());
            return $this->createdResponse($item);
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        return $this->successResponse($this->service->find($id));
    }

    public function update(TicketCategoryUpdateRequest $request,int $id)
    {
        try{
            $this->service->update($request->validated(), $id);
            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->service->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        try{
            // $this->authorize('ticket.delete');

            $this->service->bulkDelete($request->validated());
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

}