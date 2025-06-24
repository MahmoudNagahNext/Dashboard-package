<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\Hash;
use nextdev\nextdashboard\Models\Admin;
use Spatie\Permission\Models\Role;

class AdminService
{
    // TODO:: function return type
    public function __construct(
        private Admin $model,
    ){}

    public function paginate($search = null, $with = [], $perPage = 10, $page = 1, $sortBy = 'id', $sortDirection = 'desc')
    {
        $q = $this->model::query()->with($with);

        if($search){
            $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        }

        $q->orderBy($sortBy, $sortDirection);
        
        return $q->paginate($perPage, ['*'], 'page',$page);
    }
 
    public function create(array $data)
    { 
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