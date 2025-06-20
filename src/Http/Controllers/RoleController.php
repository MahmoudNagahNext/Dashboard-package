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
    ){}
    
     public function index()
    {
        return $this->successResponse(
            RoleResource::collection($this->service->index())
        );
    }

    public function store(RoleStoreRequest $request)
    {
        try{
            $role = $this->service->store($request->validated());

            return $this->createdResponse(
                RoleResource::make($role)
            );
        } catch(\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        return $this->successResponse(
            RoleResource::make($this->service->find($id))
        );
    }

    public function update(RoleUpdateRequest $request, int $id)
    {
        try{
            $role = $this->service->update($id, $request->validated());
            return $this->updatedResponse([], "Role Updated Successfully");
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->service->delete($id);
            return $this->deletedResponse("Role Deleted Successfully");
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
