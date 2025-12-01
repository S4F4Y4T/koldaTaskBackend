<?php

namespace App\Services\V1;

use App\DTOs\V1\TaskDTO;
use App\Filters\V1\QueryFilter;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service class for handling task business logic
 * 
 * This service encapsulates all task-related operations including
 * creation, updates, deletion, and assignment.
 */
class TaskService
{
    /**
     * Create a new task for a project
     *
     * @param TaskDTO $dto Task data transfer object
     * @return Task
     */
    public function create(TaskDTO $dto): Task
    {
        return Task::create($dto->toArray());
    }

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param TaskDTO $dto
     * @return Task
     */
    public function update(Task $task, TaskDTO $dto): Task
    {
        $task->update($dto->toArrayForUpdate());

        return $task->fresh();
    }

    /**
     * Delete a task
     *
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Assign a task to a user
     *
     * @param Task $task
     * @param User $user
     * @return Task
     */
    public function assignToUser(Task $task, User $user): Task
    {
        $task->assigned_user_id = $user->id;
        $task->save();

        return $task->fresh(['assignedUser']);
    }

    /**
     * Get all tasks with optional filtering
     *
     * @param QueryFilter|null $filter
     * @return Collection
     */
    public function getAll(?QueryFilter $filter = null): Collection
    {
        $query = Task::with(['project', 'assignedUser']);

        if ($filter) {
            $query->filter($filter);
        }

        return $query->get();
    }

    /**
     * Get tasks for a specific project
     *
     * @param Project $project
     * @return Collection
     */
    public function getByProject(Project $project): Collection
    {
        return Task::with(['assignedUser'])
            ->forProject($project->id)
            ->get();
    }

    /**
     * Get tasks assigned to a specific user
     *
     * @param User $user
     * @return Collection
     */
    public function getByAssignedUser(User $user): Collection
    {
        return Task::with(['project'])
            ->assignedTo($user->id)
            ->get();
    }

    /**
     * Get overdue tasks
     *
     * @return Collection
     */
    public function getOverdue(): Collection
    {
        return Task::with(['project', 'assignedUser'])
            ->overdue()
            ->get();
    }

    /**
     * Mark a task as completed
     *
     * @param Task $task
     * @return Task
     */
    public function markAsCompleted(Task $task): Task
    {
        $task->markAsCompleted();

        return $task->fresh();
    }
}
