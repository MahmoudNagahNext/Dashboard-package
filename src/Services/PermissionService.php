<?php 

namespace nextdev\nextdashboard\Services;

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
            return explode('.', $perm->name)[0];
        });

        return $grouped; 
    }
}