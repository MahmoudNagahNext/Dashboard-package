<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Exception;
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
    ) {}

    public function index()
    {
        // if (!auth()->guard('admin')->user()->hasPermissionTo('role.view')) {
        //     return $this->errorResponse('Unauthorized.', 403);
        // }
        return $this->successResponse(
            RoleResource::collection($this->service->index())
        );
    }

    public function store(RoleStoreRequest $request)
    {
        if (!auth()->guard('admin')->user()->hasPermissionTo('role.create')) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $role = $this->service->store($request->validated());

        return $this->createdResponse(
            RoleResource::make($role)
        );
    }

    public function show(int $id)
    {
        if (!auth()->guard('admin')->user()->hasPermissionTo('role.view')) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        return $this->successResponse(
            RoleResource::make($this->service->find($id))
        );
    }

    public function update(RoleUpdateRequest $request, int $id)
    {

        if (!auth()->guard('admin')->user()->hasPermissionTo('role.update')) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $role = $this->service->update($id, $request->validated());
        return $this->updatedResponse([], "Role Updated Successfully");
    }

    public function destroy(int $id)
    {

        if (!auth()->guard('admin')->user()->hasPermissionTo('role.delete')) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $this->service->delete($id);
        return $this->deletedResponse("Role Deleted Successfully");
    }
}
