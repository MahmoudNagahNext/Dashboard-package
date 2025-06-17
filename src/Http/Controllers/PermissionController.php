<?php 
namespace nextdev\nextdashboard\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Services\PermissionService;
use nextdev\nextdashboard\Traits\ApiResponseTrait;


class PermissionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected PermissionService $service
    ){}
    public function index()
    {
        $permissions = $this->service->groupedPermissions();
        return $this->successResponse($permissions, "Permissions Pagination");
    }
}
