<?php 

namespace nextdev\nextdashboard\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\TicketReply;

class TicketReplyService
{
    public function __construct(
        protected TicketReply $model,
    ){}

    public function index(int $ticketId)
    {
        return $this->model->where('ticket_id', $ticketId)
            ->with(['user', 'media'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find(int $id)
    {
        return $this->model->with(['user', 'media'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();
            $attachments = $data['attachments'] ?? null;
            unset($data['attachments']);
            
            $reply = $this->model->create($data);

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $reply->addMedia($attachment)->toMediaCollection('attachments');
                }
            }

            return $reply->load(['user', 'media']);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $reply = $this->model->findOrFail($id);
            $attachments = $data['attachments'] ?? null;
            unset($data['attachments']);

            $reply->update($data);

            if ($attachments) {
                $reply->clearMediaCollection('attachments');
                
                foreach ($attachments as $attachment) {
                    $reply->addMedia($attachment)->toMediaCollection('attachments');
                }
            }

            return $reply->load(['user', 'media']);
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $reply = $this->model->findOrFail($id);
            $reply->clearMediaCollection('attachments');
            return $reply->delete();
        });
    }
}