<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Models\Admin;
use Spatie\Permission\Models\Role;

class AdminService
{
    public function __construct(
        private Admin $model,
    ){}

    public function paginate()
    {
        return $this->model::query()->paginate(10);
    }
 
    public function create(AdminDTO $dto)
    { 
        return $this->model::create([
            'name'=> $dto->name,
            'email'=> $dto->email,
            'password'=> Hash::make($dto->password),
        ]);
    }

    public function find(int $id)
    {
        return $this->model::query()->find($id);
    }
 
    public function update(array $data, $id)
    {
        $user = $this->model::query()->find($id);
        return $user->update($data);
    }
 
    public function delete(int $id)
    {
        $user = $this->model::query()->find($id);
        return $user->delete();
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