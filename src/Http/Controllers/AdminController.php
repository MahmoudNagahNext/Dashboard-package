<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Events\AdminCreated;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Admin\AdminStoreRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AdminUpdateRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AssignRoleRequest;
use nextdev\nextdashboard\Http\Requests\Admin\BulkDeleteRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Services\AdminService;

class AdminController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AdminService $adminService
    ){}

    public function index()
    {
        try{
            dd(auth()->guard('admin')->user());
            if (!auth()->guard('admin')->user()->hasPermissionTo('admin.view')) {
                return $this->errorResponse('Unauthorized.', 403);
            }
            
            $admins = $this->adminService->paginate();
            return $this->paginatedCollectionResponse($admins,'Admins Paginated', [], AdminResource::class);    
        } catch (\Exception $e) {
             return $this->handleException($e);
        }
    }

    public function store(AdminStoreRequest $request)
    {
        try{
            // $this->authorize('admin.create');

            $dto = AdminDTO::fromRequest($request->validated());
            $admin = $this->adminService->create($dto);

            event(new AdminCreated($admin));

            return $this->createdResponse(AdminResource::make($admin));
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try{
            // $this->authorize('admin.view');

            return $this->successResponse(AdminResource::make($this->adminService->find($id)));
        } catch (\Exception $e){
             return $this->handleException($e);
        }
    }

    public function update(AdminUpdateRequest $request,int $id)
    {
        try{
            // $this->authorize('admin.update');

            $this->adminService->update($request->validated(), $id);
            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            // $this->authorize('admin.delete');

            $this->adminService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function assignRole(AssignRoleRequest $request, int $id)
    {
        try{
            // $this->authorize('admin.assign_role');

            $admin = $this->adminService->AssignRole($request->validated()['role_id'], $id);

            // event(new RoleAssignedToAdmin($admin, $role));
            return $this->successResponse($admin);
        } catch (\Exception $e) {
            return $this->handleException($e);   
        }
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        try{
            // $this->authorize('admin.delete');

            $this->adminService->bulkDelete($request->validated()['ids']);
            return $this->deletedResponse('Admins deleted successfully');
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}