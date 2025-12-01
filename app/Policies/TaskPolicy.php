<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

/**
 * Authorization policy for Task model
 * 
 * Defines who can perform various actions on tasks.
 */
class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can list tasks
        return true;
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        // Users can view tasks in their projects or assigned to them
        return $user->id === $task->project->created_by ||
            $user->id === $task->assigned_user_id;
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function create(User $user, Project $project): bool
    {
        // Only the project creator can create tasks in the project
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        // Project creator or assigned user can update the task
        return $user->id === $task->project->created_by ||
            $user->id === $task->assigned_user_id;
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function delete(User $user, Task $task): bool
    {
        // Only the project creator can delete tasks
        return $user->id === $task->project->created_by;
    }
}
