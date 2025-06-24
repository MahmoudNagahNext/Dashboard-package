<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $models = [
            'ticket' => ['create', 'view', 'update', 'delete'],
            'admin'  => ['create', 'view', 'update', 'delete','assign_role'],
            'role'   => ['create', 'view', 'update', 'delete']
        ];

        $permissions = [];

        foreach ($models as $model => $actions) {
            foreach ($actions as $action) {
                $permName = "$model.$action";
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'admin']);
                $permissions[] = $permName;
            }
        }

        // Assign to admin
        $adminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);
        $adminRole->syncPermissions($permissions);
    }
}
