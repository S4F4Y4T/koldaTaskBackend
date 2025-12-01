<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query filter for Task model
 * 
 * Provides filtering and sorting capabilities for task queries.
 */
class TaskFilter extends QueryFilter
{
    /**
     * Sortable fields
     *
     * @var array<string, string>
     */
    protected array $sort = [
        'title' => 'title',
        'deadline' => 'deadline',
        'status' => 'status',
        'created_at' => 'created_at',
    ];

    /**
     * Filter by task status
     *
     * @param string $status
     * @return void
     */
    public function status(string $status): void
    {
        $this->builder->where('status', $status);
    }

    /**
     * Filter by project ID
     *
     * @param int $projectId
     * @return void
     */
    public function projectId(int $projectId): void
    {
        $this->builder->where('project_id', $projectId);
    }

    /**
     * Filter by assigned user ID
     *
     * @param int $userId
     * @return void
     */
    public function assignedUserId(int $userId): void
    {
        $this->builder->where('assigned_user_id', $userId);
    }

    /**
     * Search by task title
     *
     * @param string $title
     * @return void
     */
    public function title(string $title): void
    {
        $this->builder->where('title', 'like', "%{$title}%");
    }

    /**
     * Filter by overdue tasks
     *
     * @param bool $overdue
     * @return void
     */
    public function overdue(bool $overdue): void
    {
        if ($overdue) {
            $this->builder->where('deadline', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled']);
        }
    }

    /**
     * Filter by deadline (from)
     *
     * @param string $date
     * @return void
     */
    public function deadlineFrom(string $date): void
    {
        $this->builder->where('deadline', '>=', $date);
    }

    /**
     * Filter by deadline (to)
     *
     * @param string $date
     * @return void
     */
    public function deadlineTo(string $date): void
    {
        $this->builder->where('deadline', '<=', $date);
    }
}
