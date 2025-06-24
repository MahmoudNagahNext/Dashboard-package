<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\Request;
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
    ) {}

    public function index(Request $request)
    {
        // TODO:: add search and filters

        $items = $this->service->paginate(
            $request->input('search'),
            [],
            $request->input('per_page', 10),
            $request->input('page', 1),
            $request->input('sort_by', 'id'),
            $request->input('sort_direction', 'desc'),
            $request->input('filters', [])
        );
        
        return $this->paginatedResponse($items);
    }

    public function store(TicketCategoryStoreRequest $request)
    {
        $item = $this->service->create($request->validated());
        return $this->createdResponse($item);
    }

    public function show(int $id)
    {
        return $this->successResponse($this->service->find($id));
    }

    public function update(TicketCategoryUpdateRequest $request, int $id)
    {

        $this->service->update($request->validated(), $id);
        return $this->updatedResponse();
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return $this->deletedResponse();
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        // $this->authorize('ticket.delete');

        $this->service->bulkDelete($request->validated()['ids']);
        return $this->deletedResponse('Ticket Categories deleted successfully');
    }
}
