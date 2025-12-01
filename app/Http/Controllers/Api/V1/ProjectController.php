<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\ProjectDTO;
use App\Filters\V1\ProjectFilter;
use App\Http\Requests\V1\Project\StoreProjectRequest;
use App\Http\Requests\V1\Project\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use App\Services\V1\ProjectService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Project Controller
 * 
 * Handles all project-related HTTP requests with proper authorization
 * and optimized database queries.
 */
class ProjectController
{
    use ApiResponse;

    /**
     * Create a new ProjectController instance
     *
     * @param ProjectService $projectService
     */
    public function __construct(
        protected ProjectService $projectService
    ) {
    }

    /**
     * Display a listing of projects
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $filter = ProjectFilter::init();
        $projects = $this->projectService->getAll($filter);

        return self::success(
            'Projects retrieved successfully.',
            data: ProjectResource::collection($projects)
        );
    }

    /**
     * Store a newly created project
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $dto = ProjectDTO::fromRequest($request);
        $project = $this->projectService->create($dto);

        return self::success(
            'Project created successfully.',
            201,
            ProjectResource::make($project->load('creator'))
        );
    }

    /**
     * Display the specified project with tasks
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $projectWithTasks = $this->projectService->getWithTasks($project->id);

        return self::success(
            'Project retrieved successfully.',
            data: ProjectResource::make($projectWithTasks)
        );
    }

    /**
     * Update the specified project
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return JsonResponse
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $dto = ProjectDTO::fromRequest($request);
        $updatedProject = $this->projectService->update($project, $dto);

        return self::success(
            'Project updated successfully.',
            data: ProjectResource::make($updatedProject->load('creator'))
        );
    }

    /**
     * Remove the specified project
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project);

        return self::success('Project deleted successfully.');
    }

    /**
     * Helper method to authorize actions
     *
     * @param string $ability
     * @param mixed $arguments
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorize(string $ability, mixed $arguments = []): void
    {
        app(\Illuminate\Contracts\Auth\Access\Gate::class)->authorize($ability, $arguments);
    }
}
