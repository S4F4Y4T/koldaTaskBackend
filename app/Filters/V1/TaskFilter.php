<?php

namespace App\Filters\V1;

class TaskFilter extends QueryFilter
{
    protected array $sort = [
        'title' => 'title',
        'deadline' => 'deadline',
        'status' => 'status',
        'created_at' => 'created_at',
    ];

    public function status(string $status): void
    {
        $this->builder->where('status', $status);
    }

    public function projectId(int $projectId): void
    {
        $this->builder->where('project_id', $projectId);
    }

    public function assignedUserId(int $userId): void
    {
        $this->builder->where('assigned_user_id', $userId);
    }

    public function title(string $title): void
    {
        $this->builder->where('title', 'like', "%{$title}%");
    }

    public function overdue(bool $overdue): void
    {
        if ($overdue) {
            $this->builder->where('deadline', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled']);
        }
    }

    public function deadlineFrom(string $date): void
    {
        $this->builder->where('deadline', '>=', $date);
    }

    public function deadlineTo(string $date): void
    {
        $this->builder->where('deadline', '<=', $date);
    }
}
