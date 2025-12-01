<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\TaskCreated;
use App\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Task Model
 * 
 * Represents a task within a project, assigned to a user.
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'deadline',
        'assigned_user_id',
        'status',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'status' => TaskStatus::class,
        ];
    }

    /**
     * Get the project that owns the task
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to the task
     *
     * @return BelongsTo
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Scope to filter tasks using QueryFilter
     *
     * @param Builder $query
     * @param QueryFilter $filter
     * @return void
     */
    public function scopeFilter(Builder $query, QueryFilter $filter): void
    {
        $filter->apply($query);
    }

    /**
     * Scope to filter tasks by status
     *
     * @param Builder $query
     * @param TaskStatus|string $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, TaskStatus|string $status): Builder
    {
        $statusValue = $status instanceof TaskStatus ? $status->value : $status;
        return $query->where('status', $statusValue);
    }

    /**
     * Scope to get overdue tasks
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('deadline', '<', now())
            ->whereNotIn('status', [
                TaskStatus::COMPLETED->value,
                TaskStatus::CANCELLED->value
            ]);
    }

    /**
     * Scope to get tasks for a specific project
     *
     * @param Builder $query
     * @param int $projectId
     * @return Builder
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get tasks assigned to a specific user
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_user_id', $userId);
    }

    /**
     * Check if the task is overdue
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->deadline->isPast() &&
            $this->status !== TaskStatus::COMPLETED &&
            $this->status !== TaskStatus::CANCELLED;
    }

    /**
     * Check if the task is completed
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::COMPLETED;
    }

    /**
     * Mark the task as completed
     *
     * @return bool
     */
    public function markAsCompleted(): bool
    {
        $this->status = TaskStatus::COMPLETED;
        return $this->save();
    }
}
