<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\Auth;
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

        $ticket = $this->model::create($data);

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $ticket->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $ticket; 
    }

     public function find(int $id,array $with = [])
     {
         return $this->model::query()->with($with)->find($id);
     }
 
     public function update(array $data, $id)
     {
        $ticket = $this->model->find($id);
        if($data['assignee_id']){
            $data['assignee_type'] = Admin::class;
        }
        return $ticket->update($data);
     }
 
     public function delete(int $id)
     {
         $ticket = $this->model::query()->find($id);
         return $ticket->delete();
     }
}