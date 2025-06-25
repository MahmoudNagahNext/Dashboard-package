<?php

namespace nextdev\nextdashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status'      => $this->status?->value ?? 'N/A',
            'priority'    => $this->priority?->value ?? 'N/A',
            'category' => $this->category->name ?? 'N/A',
            'created_by' => $this->creator->name ?? 'N/A',
            'assigned_to' => $this->assignee->name ?? 'N/A',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'attachments' => $this->getMedia('attachments')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'url' => $media->getFullUrl(),
                    'size' => $media->size,
                    'created_at' => $media->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
