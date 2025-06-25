<?php

namespace nextdev\nextdashboard\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use nextdev\nextdashboard\Models\TicketReply;

class TicketReplyService
{
    public function __construct(
        protected TicketReply $model,
    ) {}

    public function index(int $ticketId): Collection
    {
        return $this->model->where('ticket_id', $ticketId)
            ->with(['admin', 'media'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find(int $id): TicketReply
    {
        return $this->model->with(['admin', 'media'])->findOrFail($id);
    }

    public function create(array $data): TicketReply
    {
        $data['admin_id'] = Auth::id();
        $attachments = $data['attachments'] ?? null;

        $reply = $this->model->create($data);

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $reply->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $reply->load(['admin', 'media']);
    }

    public function update(int $id, array $data):bool|null
    {
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

        return $reply->load(['admin', 'media']);
    }

    public function delete(int $id): bool|null
    {
        $reply = $this->model->findOrFail($id);
        $reply->clearMediaCollection('attachments');
        return $reply->delete();
    }
}
