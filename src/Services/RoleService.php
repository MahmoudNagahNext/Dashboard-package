<?php

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{

    public function __construct(
        private Role $model,
    ) {}
    
    public function paginate($search = null, $with = [], $perPage = 10, $page = 1, $sortBy = 'id', $sortDirection = 'desc')
    {
        $q = $this->model::query()->with($with);

        if ($search) {
            $q->where('name', 'like', "%{$search}%");
        }

        $q->orderBy($sortBy, $sortDirection);

        return $q->paginate($perPage, ['*'], 'page', $page);
    }


    public function store(array $data): Role
    {
        $role = $this->model::query()->create(['name' => $data['name']]);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
            // $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    public function find(int $id, $with = []): Role
    {
        return  $this->model::query()->with($with)->findOrFail($id);
    }

    public function update(int $id, array $data): Role
    {
        $role = $this->model::query()->findOrFail($id);

        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
            // $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    public function delete(int $id): bool|null
    {
        $role = $this->model::query()->findOrFail($id);
        $role->delete();

        return $role->delete();
    }
}
