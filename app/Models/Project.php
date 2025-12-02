<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Main
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => ProjectStatus::class,
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', ProjectStatus::CANCELLED->value);
    }

    public function scopeByStatus(Builder $query, ProjectStatus|string $status): Builder
    {
        $statusValue = $status instanceof ProjectStatus ? $status->value : $status;

        return $query->where('status', $statusValue);
    }

    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    public function isOverdue(): bool
    {
        return $this->end_date->isPast() &&
            $this->status !== ProjectStatus::COMPLETED &&
            $this->status !== ProjectStatus::CANCELLED;
    }

    public function completionPercentage(): float
    {
        if ($this->relationLoaded('tasks')) {
            $totalTasks = $this->tasks->count();

            if ($totalTasks === 0) {
                return 0.0;
            }

            $completedTasks = $this->tasks
                ->where('status', \App\Enums\TaskStatus::COMPLETED)
                ->count();

            return round(($completedTasks / $totalTasks) * 100, 2);
        }

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
