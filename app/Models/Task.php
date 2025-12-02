<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\TaskCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Main
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'deadline',
        'assigned_user_id',
        'status',
    ];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'status' => TaskStatus::class,
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function scopeByStatus(Builder $query, TaskStatus|string $status): Builder
    {
        $statusValue = $status instanceof TaskStatus ? $status->value : $status;

        return $query->where('status', $statusValue);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('deadline', '<', now())
            ->whereNotIn('status', [
                TaskStatus::COMPLETED->value,
                TaskStatus::CANCELLED->value,
            ]);
    }

    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_user_id', $userId);
    }

    public function isOverdue(): bool
    {
        return $this->deadline->isPast() &&
            $this->status !== TaskStatus::COMPLETED &&
            $this->status !== TaskStatus::CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::COMPLETED;
    }

    public function markAsCompleted(): bool
    {
        $this->status = TaskStatus::COMPLETED;

        return $this->save();
    }
}
