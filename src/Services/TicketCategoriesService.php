<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use nextdev\nextdashboard\Models\TicketCategory;

class TicketCategoriesService
{
    public function __construct(
        protected TicketCategory $model
    ){}

    public function paginate($search = null, $with = [], $perPage = 10, $page = 1, $sortBy = 'id', $sortDirection = 'desc', $filters = []): LengthAwarePaginator
    {
        $q = $this->model::query()->with($with);

        if ($search) {
            $q->where('name', 'like', "%{$search}%");
        }

        // Apply filters
        foreach ($filters as $field => $value) {
            if (!is_null($value)) {
                $q->where($field, $value);
            }
        }

        $q->orderBy($sortBy, $sortDirection);

        return $q->paginate($perPage, ['*'], 'page', $page);
    }
 
    public function create(array $data): TicketCategory
    { 
        return $this->model::create($data);
    }

    public function find(int $id): TicketCategory
    {
        return $this->model::query()->find($id);
    }

    public function update(array $data, $id): bool|null
    {
        $item = $this->model::query()->find($id);
        return $item->update($data);
    }

    public function delete(int $id): bool|null
    {
        $item = $this->model::query()->find($id);
        return $item->delete();
    }

    public function bulkDelete(array $ids): bool|null
    {
        return $this->model::query()->whereIn('id', $ids)->delete();
    }
}