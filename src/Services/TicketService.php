<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use nextdev\nextdashboard\DTOs\TicketDTO;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\Ticket;

class TicketService
{

    public function __construct(
        protected Ticket $model,
    ){}

    public function paginate(array $with)
    {
        return $this->model::query()->with($with)->paginate(10);
    }
 
    public function create(TicketDTO $dto)
    {   
        $data = (array) $dto;
        $attachments = $data['attachments'] ?? null;
        unset($data['attachments']);

        $data['creator_id'] = Auth::user()->id;
        $data['creator_type'] = Admin::class;

        return DB::transaction(function() use ($data, $attachments){
            $ticket = $this->model::create($data);

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $ticket->addMedia($attachment)->toMediaCollection('attachments');
                }
            }
            return $ticket;
        });
    }

     public function find(int $id,array $with = [])
     {
         return $this->model::query()->with($with)->find($id);
     }
 
    public function update(TicketDTO $dto, $id)
    {
        $ticket = $this->model->find($id);

        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'status_id' => $dto->status_id,
            'priority_id' => $dto->priority_id,
            'category_id' => $dto->category_id,
            'creator_type' => $ticket->creator_type,
            'creator_id' => $ticket->creator_id,
            'assignee_type' => $dto->assignee_id ? Admin::class : null,
            'assignee_id' => $dto->assignee_id,
        ];

        return DB::transaction(function() use($data, $dto, $ticket){

            $ticket->update($data);

            if (!empty($dto->attachments)) {
                $ticket->clearMediaCollection('attachments');

                foreach ($dto->attachments as $file) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }
            return $ticket;
        });
    }
 
    public function delete(int $id)
    {
        return DB::transaction(function () use($id) {
            $ticket = $this->model::query()->find($id);
            $ticket->clearMediaCollection('attachments');
            return $ticket->delete(); 
        });
    }

    public function bulkDelete(array $ids)
    {
        return $this->model::query()->whereIn('id', $ids)->delete();
    }
}