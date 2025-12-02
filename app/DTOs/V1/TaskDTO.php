<?php

namespace App\DTOs\V1;

use Illuminate\Http\Request;

class TaskDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly string $deadline,
        public readonly ?int $assigned_user_id = null,
        public readonly ?int $project_id = null,
    ) {}

    public static function fromRequest(Request $request, ?int $projectId = null, ?\App\Models\Task $task = null): self
    {
        return new self(
            title: $request->validated('title') ?? $task?->title,
            description: $request->validated('description') ?? $task?->description,
            status: $request->validated('status') ?? $task?->status->value,
            deadline: $request->validated('deadline') ?? $task?->deadline?->format('Y-m-d H:i:s'),
            assigned_user_id: $request->validated('assigned_user_id') ?? $task?->assigned_user_id,
            project_id: $projectId ?? $task?->project_id,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            status: $data['status'],
            deadline: $data['deadline'],
            assigned_user_id: $data['assigned_user_id'],
            project_id: $data['project_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'assigned_user_id' => $this->assigned_user_id,
            'project_id' => $this->project_id,
        ], fn ($value) => ! is_null($value));
    }
}
