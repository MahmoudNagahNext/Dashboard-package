<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\TicketCategory\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Requests\TicketCategory\TicketCategoryStoreRequest;
use nextdev\nextdashboard\Http\Requests\TicketCategory\TicketCategoryUpdateRequest;
use nextdev\nextdashboard\Services\TicketCategoriesService;

class TicketCategoriesController extends Controller
{
    public function __construct(
        protected TicketCategoriesService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $items = $this->service->paginate(
            $request->input('search'),
            [],
            $request->input('per_page', 10),
            $request->input('page', 1),
            $request->input('sort_by', 'id'),
            $request->input('sort_direction', 'desc'),
            $request->input('filters', [])
        );

        return Response::json([
            'success' => true,
            'message' => "Ticket Categories Fetched Successfully",
            'data'    => $items
        ],200);
    }

    public function store(TicketCategoryStoreRequest $request): JsonResponse
    {
        $item = $this->service->create($request->validated());
        return Response::json([
            'success' => true,
            'message' => "Ticket Category Created Successfully",
            'data'    => $item
        ],201);
    }

    public function show(int $id): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => "Ticket Category Fetched Successfully",
            'data'    => $this->service->find($id)
        ],200);
    }

    public function update(TicketCategoryUpdateRequest $request, int $id): JsonResponse
    {

        $this->service->update($request->validated(), $id);
        return Response::json([
            'success' => true,
            'message' => "Ticket Category Updated Successfully",
            'data'    => []
        ],200);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return Response::json([
            'success' => true,
            'message' => "Ticket Category Deleted Successfully",
            'data'    => []
        ],200);
    }

    public function bulkDelete(BulkDeleteRequest $request): JsonResponse
    {
        $this->service->bulkDelete($request->validated()['ids']);
        return Response::json([
            'success' => true,
            'message' => "Ticket Categories Deleted Successfully",
            'data'    => []
        ],200);
    }
}
