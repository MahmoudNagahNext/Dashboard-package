<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Models\Admin;
use Spatie\Permission\Models\Role;

class AdminService
{
    // TODO:: function return type
    public function __construct(
        private Admin $model,
    ){}

    // TODO:: add search and filters
    public function paginate()
    {
        return $this->model::query()->paginate(10);
    }
 
    public function create(array $data)
    { 
        //TODO:: remove transaction 
        return $this->model::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'password'=> Hash::make($data['password']),
        ]);
    }

    public function find(int $id)
    {
        return $this->model::query()->find($id);
    }
 
    public function update(array $data, $id)
    {
        return $this->model::query()->find($id)->update($data);
    }
 
    public function delete(int $id)
    {
        return  $this->model::query()->find($id)->delete();
    }

    public function AssignRole(int $roleId, int $adminId)
    {
        $admin = $this->model::findOrFail($adminId);
        $role = Role::findOrFail($roleId);

        $admin->syncRoles([$role->name]);

        return $admin->load('roles');
    }

    public function bulkDelete(array $ids)
    {
        return $this->model::query()->whereIn('id', $ids)->delete();
    }
}