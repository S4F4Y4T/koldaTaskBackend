<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/**
 * Authorization policy for Project model
 * 
 * Defines who can perform various actions on projects.
 */
class ProjectPolicy
{
    /**
     * Determine whether the user can view any projects.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can list projects
        return true;
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function view(User $user, Project $project): bool
    {
        // Users can view projects they created or have tasks in
        return $user->id === $project->created_by ||
            $project->tasks()->where('assigned_user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // All authenticated users can create projects
        return true;
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function update(User $user, Project $project): bool
    {
        // Only the project creator can update it
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function delete(User $user, Project $project): bool
    {
        // Only the project creator can delete it
        return $user->id === $project->created_by;
    }
}
