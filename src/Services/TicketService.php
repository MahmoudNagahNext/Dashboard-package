<?php

namespace nextdev\nextdashboard\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\Ticket;

class TicketService
{

    public function __construct(
        protected Ticket $model,
    ) {}

    public function paginate($search = null, $with = [], $perPage = 10, $page = 1, $sortBy = 'id', $sortDirection = 'desc', $filters = []): LengthAwarePaginator
    {
        $q = $this->model::query()->with($with);

        if ($search) {
            $q->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
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

    public function create(array $data): Ticket
    {
        $attachments = $data['attachments'] ?? null;

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

    public function find(int $id, array $with = []): Ticket
    {
        return $this->model::query()->with($with)->findOrFail($id);
    }

    public function updateTicket(array $data, $id): bool|null
    {
        $ticket = $this->model->findOrFail($id);
        $ticket->update($data);
        return $ticket; 
    }

    public function addAttachments($id, array $attachments):Ticket
    {
        $ticket = $this->model->findOrFail($id);

        foreach ($attachments as $file) {
            $ticket->addMedia($file)->toMediaCollection('attachments');
        }

        return $ticket->getMedia('attachments');
    }

    public function deleteAttachments($id, array $mediaIds):Ticket
    {
        $ticket = $this->model->findOrFail($id);

        foreach ($mediaIds as $mediaId) {
            $media = $ticket->media()->where('id', $mediaId)->first();
            if ($media) {
                $media->delete();
            }
        }

        return $ticket->getMedia('attachments');
    }


    public function delete(int $id): bool|null
    {
        $ticket = $this->model::query()->find($id);
        $ticket->clearMediaCollection('attachments');
        return $ticket->delete();
    }

    public function bulkDelete(array $ids): bool|null
    {
        return $this->model::query()->whereIn('id', $ids)->delete();
    }
}
