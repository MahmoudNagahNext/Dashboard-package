<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\Admin\AdminStoreRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AdminUpdateRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AssignRoleRequest;
use nextdev\nextdashboard\Http\Requests\Admin\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Services\AdminService;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){
        $this->middleware('can:admin.view')->only(['index', 'show']);
        $this->middleware('can:admin.create')->only('store');
        $this->middleware('can:admin.update')->only('update');
        $this->middleware('can:admin.delete')->only(['destroy', 'bulkDelete']);
        $this->middleware('can:admin.assign_role')->only('assignRole');
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $with = ['roles'];
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $admins = $this->adminService->paginate($search, $with, $perPage, $page, $sortBy, $sortDirection);
        
        return Response::json([
            'success' => true,
            'message' => 'Admins Paginated',
            'data' => AdminResource::collection($admins),
            'meta' => [
                'current_page' => $admins->currentPage(),
                'last_page' => $admins->lastPage(),
                'per_page' => $admins->perPage(),
                'total' => $admins->total(),
            ],
        ]);   
    }

    public function store(AdminStoreRequest $request): JsonResponse
    {
        $admin = $this->adminService->create($request->validated());
        // event(new AdminCreated($admin));

        return Response::json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => AdminResource::make($admin),
        ],201);
    }

    public function show(int $id): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => 'Admin found successfully',
            'data' => AdminResource::make($this->adminService->find($id,['roles'])),
        ]);
    }

    public function update(AdminUpdateRequest $request,int $id): JsonResponse
    {
        $this->adminService->update($request->validated(), $id);
        return Response::json([
            'success' => true,
            'message' => 'Admin updated successfully',
            'data' => [],
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->adminService->delete($id);
        return Response::json([
            'success' => true,
            'message' => 'Admin deleted successfully',
            'data' => [],
        ]);
    }

    public function assignRole(AssignRoleRequest $request, int $id): JsonResponse
    {
        $admin = $this->adminService->AssignRole($request->validated()['role_id'], $id);
        // event(new RoleAssignedToAdmin($admin, $role));

        return Response::json([
            'success' => true,
            'message' => 'Admin role assigned successfully',
            'data' => [],
        ]);
    }

    public function bulkDelete(BulkDeleteRequest $request): JsonResponse
    {
        $this->adminService->bulkDelete($request->validated()['ids']);
        return Response::json([
            'success' => true,
            'message' => 'Admins deleted successfully',
            'data' => [],
        ]);
    }
}