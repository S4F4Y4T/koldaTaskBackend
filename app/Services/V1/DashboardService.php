<?php

namespace App\Services\V1;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(): array
    {
        $user = auth()->user();

        return [
            'statistics' => $this->getStatistics(),
            'project_statistics' => $this->getProjectStatistics(),
            'task_statistics' => $this->getTaskStatistics(),
            'user_tasks' => $this->getUserTaskStatistics($user),
            'recent_projects' => $this->getRecentProjects(),
            'recent_tasks' => $this->getRecentTasks(),
            'overdue_items' => $this->getOverdueItems(),
        ];
    }

    protected function getStatistics(): array
    {
        return [
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'total_users' => User::count(),
            'active_projects' => Project::where('status', ProjectStatus::IN_PROGRESS->value)->count(),
        ];
    }

    protected function getProjectStatistics(): array
    {
        $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($item) => [$item->status->value => $item->count])
            ->toArray();

        return [
            'by_status' => [
                'pending' => $projectsByStatus[ProjectStatus::PENDING->value] ?? 0,
                'in_progress' => $projectsByStatus[ProjectStatus::IN_PROGRESS->value] ?? 0,
                'completed' => $projectsByStatus[ProjectStatus::COMPLETED->value] ?? 0,
                'cancelled' => $projectsByStatus[ProjectStatus::CANCELLED->value] ?? 0,
            ],
            'overdue' => Project::whereDate('end_date', '<', now())
                ->whereNotIn('status', [
                    ProjectStatus::COMPLETED->value,
                    ProjectStatus::CANCELLED->value,
                ])
                ->count(),
        ];
    }

    protected function getTaskStatistics(): array
    {
        $tasksByStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($item) => [$item->status->value => $item->count])
            ->toArray();

        return [
            'by_status' => [
                'pending' => $tasksByStatus[TaskStatus::PENDING->value] ?? 0,
                'in_progress' => $tasksByStatus[TaskStatus::IN_PROGRESS->value] ?? 0,
                'completed' => $tasksByStatus[TaskStatus::COMPLETED->value] ?? 0,
                'cancelled' => $tasksByStatus[TaskStatus::CANCELLED->value] ?? 0,
            ],
            'overdue' => Task::overdue()->count(),
        ];
    }

    protected function getUserTaskStatistics(User $user): array
    {
        $userTasks = Task::where('assigned_user_id', $user->id);

        $tasksByStatus = (clone $userTasks)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($item) => [$item->status->value => $item->count])
            ->toArray();

        return [
            'total' => (clone $userTasks)->count(),
            'by_status' => [
                'pending' => $tasksByStatus[TaskStatus::PENDING->value] ?? 0,
                'in_progress' => $tasksByStatus[TaskStatus::IN_PROGRESS->value] ?? 0,
                'completed' => $tasksByStatus[TaskStatus::COMPLETED->value] ?? 0,
                'cancelled' => $tasksByStatus[TaskStatus::CANCELLED->value] ?? 0,
            ],
            'overdue' => (clone $userTasks)->overdue()->count(),
        ];
    }

    protected function getRecentProjects(int $limit = 5): array
    {
        return Project::with('tasks')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'client' => $project->client,
                    'status' => $project->status->value,
                    'start_date' => $project->start_date->format('Y-m-d'),
                    'end_date' => $project->end_date->format('Y-m-d'),
                    'completion_percentage' => $project->completionPercentage(),
                    'tasks_count' => $project->tasks->count(),
                ];
            })
            ->toArray();
    }

    protected function getRecentTasks(int $limit = 10): array
    {
        return Task::with(['project', 'assignedUser'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status->value,
                    'deadline' => $task->deadline->format('Y-m-d H:i:s'),
                    'is_overdue' => $task->isOverdue(),
                    'project' => [
                        'id' => $task->project->id,
                        'title' => $task->project->title,
                    ],
                    'assigned_user' => [
                        'id' => $task->assignedUser->id,
                        'name' => $task->assignedUser->name,
                    ],
                ];
            })
            ->toArray();
    }

    protected function getOverdueItems(): array
    {
        return [
            'projects' => Project::whereDate('end_date', '<', now())
                ->whereNotIn('status', [
                    ProjectStatus::COMPLETED->value,
                    ProjectStatus::CANCELLED->value,
                ])
                ->with('tasks')
                ->get()
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->title,
                        'client' => $project->client,
                        'end_date' => $project->end_date->format('Y-m-d'),
                        'days_overdue' => now()->diffInDays($project->end_date),
                    ];
                })
                ->toArray(),
            'tasks' => Task::overdue()
                ->with(['project', 'assignedUser'])
                ->get()
                ->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'deadline' => $task->deadline->format('Y-m-d H:i:s'),
                        'days_overdue' => now()->diffInDays($task->deadline),
                        'project' => [
                            'id' => $task->project->id,
                            'title' => $task->project->title,
                        ],
                        'assigned_user' => [
                            'id' => $task->assignedUser->id,
                            'name' => $task->assignedUser->name,
                        ],
                    ];
                })
                ->toArray(),
        ];
    }
}
