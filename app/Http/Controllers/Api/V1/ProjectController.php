<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\ProjectDTO;
use App\Filters\V1\ProjectFilter;
use App\Http\Requests\V1\Project\StoreProjectRequest;
use App\Http\Requests\V1\Project\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ProjectController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $this->authorize('all', Project::class);

        $filter = ProjectFilter::init();
        $projects = Project::with(['creator', 'tasks'])
            ->filter($filter)
            ->get();

        return self::success(
            'Projects retrieved successfully.',
            data: ProjectResource::collection($projects)
        );
    }
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $dto = ProjectDTO::fromRequest($request);
        $project = Project::create($dto->toArray());

        return self::success(
            'Project created successfully.',
            201,
            ProjectResource::make($project->load('creator'))
        );
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $project->load(['tasks.assignedUser', 'creator']);

        return self::success(
            'Project retrieved successfully.',
            data: ProjectResource::make($project)
        );
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project->update(ProjectDTO::fromRequest($request)->toArray());

        return self::success(
            'Project updated successfully.',
            data: ProjectResource::make($project->load('creator'))
        );
    }
    
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return self::success('Project deleted successfully.');
    }
}
