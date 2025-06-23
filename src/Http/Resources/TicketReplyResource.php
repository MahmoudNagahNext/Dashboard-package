<?php

namespace nextdev\nextdashboard\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
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
            'ticket_id' => $this->ticket_id,
            'admin_id' => $this->admin_id,
            'body' => $this->body,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            // 'updated_at' => $this->updated_at,
            'admin' => $this->whenLoaded('admin', function() {
                return [
                    'id' => $this->admin->id,
                    'name' => $this->admin->name,
                    'email' => $this->admin->email,
                ];
            }),
            'media' => $this->whenLoaded('media', function() {
                return $this->media->map(function($media) {
                    return [
                        'id' => $media->id,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                    ];
                });
            }),
        ];
    }
}