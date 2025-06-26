<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Database\Eloquent\Collection;
use nextdev\nextdashboard\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    
    public function __construct(
        private Permission $model,
    ){}

    

    public function groupedPermissions()
    {
        $permissions = $this->model::all();

        $grouped = $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0]; // مثل: admin.create → admin
        });

        $formatted = $grouped->map(function ($group) {
            return PermissionResource::collection($group);
        });

        return $formatted; 
    }
}