<?php

namespace nextdev\nextdashboard\DTOs;

class TicketDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly ?int $status_id,
        public readonly ?int $priority_id,
        public readonly ?int $category_id,
        public readonly ?string $creator_type = null,
        public readonly ?int $creator_id = null,
        public readonly ?string $assignee_type = null,
        public readonly ?int $assignee_id = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            status_id: $data['status_id'] ?? null,
            priority_id: $data['priority_id'] ?? null,
            category_id: $data['category_id'] ?? null,
            creator_type: $data['creator_type'] ?? null,
            creator_id: $data['creator_id'] ?? null,
            assignee_type: $data['assignee_type'] ?? null,
            assignee_id: $data['assignee_id'] ?? null,
        );
    }
}