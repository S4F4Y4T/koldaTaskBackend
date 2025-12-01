<?php

namespace App\DTOs\V1;

use Illuminate\Http\Request;

class TaskDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly string $priority,
        public readonly string $due_date,
        public readonly int $assigned_to,
        public readonly ?int $project_id = null,
    ) {}

    public static function fromRequest(Request $request, ?int $projectId = null): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status'),
            priority: $request->validated('priority'),
            due_date: $request->validated('due_date'),
            assigned_to: $request->validated('assigned_to'),
            project_id: $projectId,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            status: $data['status'],
            priority: $data['priority'],
            due_date: $data['due_date'],
            assigned_to: $data['assigned_to'],
            project_id: $data['project_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'assigned_to' => $this->assigned_to,
            'project_id' => $this->project_id,
        ], fn($value) => !is_null($value));
    }

}
