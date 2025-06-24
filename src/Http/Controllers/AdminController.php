<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Admin\AdminStoreRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AdminUpdateRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AssignRoleRequest;
use nextdev\nextdashboard\Http\Requests\Admin\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Services\AdminService;

class AdminController extends Controller
{
    // TODO:: Delete ApiResponseTrait use responce Facades
    use ApiResponseTrait;

    public function __construct(
        protected AdminService $adminService
    ){
        $this->middleware('can:admin.view')->only(['index', 'show']);
        $this->middleware('can:admin.create')->only('store');
        $this->middleware('can:admin.update')->only('update');
        $this->middleware('can:admin.delete')->only(['destroy', 'bulkDelete']);
        $this->middleware('can:admin.assign_role')->only('assignRole');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $with = ['roles'];
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $admins = $this->adminService->paginate($search, $with, $perPage, $page, $sortBy, $sortDirection);
        dd($admins);
        return $this->paginatedCollectionResponse(
            $admins,
            'Admins Paginated',
            [], 
            AdminResource::class);    
    }

    public function store(AdminStoreRequest $request)
    {
        $admin = $this->adminService->create($request->validated());
        // event(new AdminCreated($admin));

        return $this->createdResponse(
            AdminResource::make($admin),
            'Admin created successfully'
        );
    }

    public function show(int $id)
    {
        return $this->successResponse(
            AdminResource::make($this->adminService->find($id)),
            'Admin found successfully'
        );
    }

    public function update(AdminUpdateRequest $request,int $id)
    {
        $this->adminService->update($request->validated(), $id);
        return $this->updatedResponse(
            [],
            'Admin updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $this->adminService->delete($id);
        return $this->deletedResponse('Admin deleted successfully');
    }

    public function assignRole(AssignRoleRequest $request, int $id)
    {
        $admin = $this->adminService->AssignRole($request->validated()['role_id'], $id);
        // event(new RoleAssignedToAdmin($admin, $role));

        return $this->successResponse(
            AdminResource::make($admin),
            'Admin role assigned successfully'
        );
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        $this->adminService->bulkDelete($request->validated()['ids']);
        return $this->deletedResponse('Admins deleted successfully');
    }
}