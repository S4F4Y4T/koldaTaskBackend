<?php

namespace App\Services\V1;

use App\Filters\V1\QueryFilter;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service class for handling project business logic
 * 
 * This service encapsulates all project-related operations including
 * creation, updates, deletion, and retrieval with optimized queries.
 */
class ProjectService
{
    /**
     * Create a new project
     *
     * @param array $data Project data
     * @param User $creator User creating the project
     * @return Project
     */
    public function create(array $data, User $creator): Project
    {
        $data['created_by'] = $creator->id;

        return Project::create($data);
    }

    /**
     * Update an existing project
     *
     * @param Project $project
     * @param array $data
     * @return Project
     */
    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project->fresh();
    }

    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function delete(Project $project): bool
    {
        return $project->delete();
    }

    /**
     * Get a project with its tasks (eager loaded)
     *
     * @param int $projectId
     * @return Project
     */
    public function getWithTasks(int $projectId): Project
    {
        return Project::with(['tasks.assignedUser', 'creator'])
            ->findOrFail($projectId);
    }

    /**
     * Get all projects with optional filtering
     *
     * @param QueryFilter|null $filter
     * @return Collection
     */
    public function getAll(?QueryFilter $filter = null): Collection
    {
        $query = Project::with(['creator', 'tasks']);

        if ($filter) {
            $query->filter($filter);
        }

        return $query->get();
    }

    /**
     * Get projects created by a specific user
     *
     * @param User $user
     * @return Collection
     */
    public function getByCreator(User $user): Collection
    {
        return Project::with(['tasks'])
            ->where('created_by', $user->id)
            ->get();
    }

    /**
     * Get active projects (non-cancelled)
     *
     * @return Collection
     */
    public function getActive(): Collection
    {
        return Project::with(['creator', 'tasks'])
            ->active()
            ->get();
    }

    /**
     * Get projects by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection
    {
        return Project::with(['creator', 'tasks'])
            ->byStatus($status)
            ->get();
    }
}
