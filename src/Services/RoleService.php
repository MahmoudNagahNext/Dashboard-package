<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    
    public function __construct(
        private Role $model,
    ){}

    public function index()
    {
            $roles = $this->model::query()->with('permissions')->get();
            return $roles;
    } 

    public function store(array $data)
    {
        return DB::transaction(function() use($data){
            $role = $this->model::query()->create(['name' => $data['name']]);

            if (!empty($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->load('permissions');
        });
    }

    public function find(int $id)
    {
        return  $this->model::query()->with('permissions')->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function() use($id, $data){
            $role = $this->model::query()->findOrFail($id);

            $role->update(['name' => $data['name']]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->load('permissions');
        });
    }

    public function delete(int $id)
    {
        $role = $this->model::query()->findOrFail($id);
        $role->delete();

        return $role->delete();
    }
   
}