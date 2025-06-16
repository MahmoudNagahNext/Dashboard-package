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
        dd($this);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->name ?? 'N/A',
            'priority' => $this->priority->name ?? 'N/A',
            'category' => $this->category->name ?? 'N/A',
            'created_by' => $this->creator->name ?? 'N/A',
            'assigned_to' => $this->assignee->name ?? 'N/A',
            "attachments" => $this->media ?? [],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
