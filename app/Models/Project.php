<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Project Model
 * 
 * Represents a project with tasks, client information, and timeline.
 */
class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'client',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => ProjectStatus::class,
        ];
    }

    /**
     * Get the user who created the project
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all tasks for the project
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Scope to filter projects using QueryFilter
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
     * Scope to get only active (non-cancelled) projects
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', ProjectStatus::CANCELLED->value);
    }

    /**
     * Scope to filter projects by status
     *
     * @param Builder $query
     * @param ProjectStatus|string $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, ProjectStatus|string $status): Builder
    {
        $statusValue = $status instanceof ProjectStatus ? $status->value : $status;
        return $query->where('status', $statusValue);
    }

    /**
     * Scope to get projects within a date range
     *
     * @param Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return Builder
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    /**
     * Check if the project is overdue
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->end_date->isPast() &&
            $this->status !== ProjectStatus::COMPLETED &&
            $this->status !== ProjectStatus::CANCELLED;
    }

    /**
     * Get the completion percentage based on completed tasks
     *
     * @return float
     */
    public function completionPercentage(): float
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0.0;
        }

        $completedTasks = $this->tasks()
            ->where('status', \App\Enums\TaskStatus::COMPLETED->value)
            ->count();

        return round(($completedTasks / $totalTasks) * 100, 2);
    }
}
