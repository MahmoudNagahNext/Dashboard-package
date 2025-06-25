<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\Role\RoleStoreRequest;
use nextdev\nextdashboard\Http\Requests\Role\RoleUpdateRequest;
use nextdev\nextdashboard\Http\Resources\RoleResource;
use nextdev\nextdashboard\Services\RoleService;


class RoleController extends Controller
{
    public function __construct(
        protected RoleService $service
    ) {
        $this->middleware('can:role.view')->only(['index', 'show']);
        $this->middleware('can:role.create')->only('store');
        $this->middleware('can:role.update')->only('update');
        $this->middleware('can:role.delete')->only('destroy');
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $with = ['permissions'];
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');

        $roles = $this->service->paginate($search, $with, $perPage, $page, $sortBy, $sortDirection);

        return Response::json([
            'success' => true,
            'message' => "Roles Pagination",
            'data' => RoleResource::collection($roles),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
            ]
        ]);
    }


    public function store(RoleStoreRequest $request): JsonResponse
    {
        $role = $this->service->store($request->validated());

        return Response::json([
            'success' => true,
            'message' => "Role Created Successfully",
            'data' => RoleResource::make($role)
        ],201);
    }

    public function show(int $id): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => "Role Fetched Successfully",
            'data' => RoleResource::make($this->service->find($id,['permissions']))
        ]);
    }

    public function update(RoleUpdateRequest $request, int $id): JsonResponse

    {
        $role = $this->service->update($id, $request->validated());
        return Response::json([
            'success' => true,
            'message' => "Role Updated Successfully",
            'data' => []
        ],200);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return Response::json([
            'success' => true,
            'message' => "Role Deleted Successfully",
            'data'    => []
        ],200);
    }
}
