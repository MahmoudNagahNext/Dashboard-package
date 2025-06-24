<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Http\Requests\Role\RoleStoreRequest;
use nextdev\nextdashboard\Http\Requests\Role\RoleUpdateRequest;
use nextdev\nextdashboard\Http\Resources\RoleResource;
use nextdev\nextdashboard\Services\RoleService;
use nextdev\nextdashboard\Traits\ApiResponseTrait;


class RoleController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected RoleService $service
    ) {
        $this->middleware('can:role.view')->only(['index', 'show']);
        $this->middleware('can:role.create')->only('store');
        $this->middleware('can:role.update')->only('update');
        $this->middleware('can:role.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $with = ['permissions'];
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');

        $roles = $this->service->paginate($search, $with, $perPage, $page, $sortBy, $sortDirection);

        return $this->paginatedCollectionResponse(
            $roles,
            'Roles Paginated',
            [],
            RoleResource::class
        );
    }


    public function store(RoleStoreRequest $request)
    {
        $role = $this->service->store($request->validated());

        return $this->createdResponse(
            RoleResource::make($role)
        );
    }

    public function show(int $id)
    {
        return $this->successResponse(
            RoleResource::make($this->service->find($id))
        );
    }

    public function update(RoleUpdateRequest $request, int $id)
    {
        $role = $this->service->update($id, $request->validated());
        return $this->updatedResponse([], "Role Updated Successfully");
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return $this->deletedResponse("Role Deleted Successfully");
    }
}
