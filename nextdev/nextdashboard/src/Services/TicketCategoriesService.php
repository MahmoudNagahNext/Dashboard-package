<?php 

namespace nextdev\nextdashboard\Services;

use nextdev\nextdashboard\Models\TicketCategory;

class TicketCategoriesService
{
    public function __construct(
        protected TicketCategory $model
    ){}

    public function paginate()
    {
        return $this->model::query()->paginate(10);
    }
 
    public function create(array $data)
    { 
        return $this->model::create($data);
    }

    public function find(int $id)
    {
        return $this->model::query()->find($id);
    }

    public function update(array $data, $id)
    {
        $item = $this->model::query()->find($id);
        return $item->update($data);
    }

    public function delete(int $id)
    {
        $item = $this->model::query()->find($id);
        return $item->delete();
    }
}