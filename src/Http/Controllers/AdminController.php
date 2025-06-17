<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Admin\AdminStoreRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AdminUpdateRequest;
use nextdev\nextdashboard\Http\Requests\Admin\AssignRoleRequest;
use nextdev\nextdashboard\Http\Requests\Auth\RegisterRequest;
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
        $admins = $this->adminService->paginate();
        return $this->paginatedCollectionResponse($admins,'Admins Paginated', [], AdminResource::class);
    }

    public function store(AdminStoreRequest $request)
    {
        try{
            $dto = AdminDTO::fromRequest($request->validated());
            $admin = $this->adminService->create($dto);
            return $this->createdResponse(AdminResource::make($admin));
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        return $this->successResponse(AdminResource::make($this->adminService->find($id)));
    }

    public function update(AdminUpdateRequest $request,int $id)
    {
        try{
            $this->adminService->update($request->validated(), $id);
            return $this->updatedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->adminService->delete($id);
            return $this->deletedResponse();
        } catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function assignRole(AssignRoleRequest $request, int $id)
    {
        $admin = $this->adminService->AssignRole($request->validated(), $id);

        return $this->successResponse($admin);
    }
}