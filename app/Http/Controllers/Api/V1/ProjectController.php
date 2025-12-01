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
use App\Http\Controllers\Api\Controller;

/**
 * Project Controller
 * 
 * Handles all project-related HTTP requests with proper authorization
 * and optimized database queries.
 */
class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {
    }

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

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $projectWithTasks = $this->projectService->getWithTasks($project->id);

        return self::success(
            'Project retrieved successfully.',
            data: ProjectResource::make($projectWithTasks)
        );
    }

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
    
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project);

        return self::success('Project deleted successfully.');
    }
}
