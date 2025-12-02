<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\TaskDTO;
use App\Filters\V1\TaskFilter;
use App\Http\Requests\V1\Task\StoreTaskRequest;
use App\Http\Requests\V1\Task\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;


class TaskController extends Controller
{

    protected string $policyModel = Task::class;

    public function index(Request $request, TaskFilter $filter): JsonResponse
    {
        $this->isAuthorized('all');

        $tasks = Task::with(['project', 'assignedUser'])
            ->filter($filter)
            ->get();

        return self::success(
            'Tasks retrieved successfully.',
            data: TaskResource::collection($tasks)
        );
    }

    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $this->isAuthorized('create');

        $task = Task::create(TaskDTO::fromRequest($request, $project->id)->toArray());

        return self::success(
            'Task created successfully.',
            201,
            TaskResource::make($task->load(['project', 'assignedUser']))
        );
    }

    public function show(Task $task): JsonResponse
    {
        $this->isAuthorized('view', $task);

        return self::success(
            'Task retrieved successfully.',
            data: TaskResource::make($task->load(['project', 'assignedUser']))
        );
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->isAuthorized('update', $task);

        $task->update(TaskDTO::fromRequest($request, $task->project_id)->toArray());

        return self::success(
            'Task updated successfully.',
            data: TaskResource::make($task->load(['project', 'assignedUser']))
        );
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->isAuthorized('delete', $task);

        $task->delete();

        return self::success('Task deleted successfully.');
    }
}
