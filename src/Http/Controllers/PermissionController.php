<?php 
namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Services\PermissionService;


class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $service
    ){}
    public function index(): JsonResponse
    {
        $permissions = $this->service->groupedPermissions();
        return Response::json([
            'success' => true,
            'message' => "Permissions Pagination",
            'data' => $permissions
        ]);
    }
}
