<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TaskFilter;
use App\Http\Requests\V1\Task\StoreTaskRequest;
use App\Http\Requests\V1\Task\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\V1\TaskService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Task Controller
 * 
 * Handles all task-related HTTP requests with proper authorization
 * and queue job dispatching for notifications.
 */
class TaskController
{
    use ApiResponse;

    /**
     * Create a new TaskController instance
     *
     * @param TaskService $taskService
     */
    public function __construct(
        protected TaskService $taskService
    ) {
    }

    /**
     * Display a listing of tasks
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $filter = TaskFilter::init();
        $tasks = $this->taskService->getAll($filter);

        return self::success(
            'Tasks retrieved successfully.',
            data: TaskResource::collection($tasks)
        );
    }

    /**
     * Store a newly created task in a project
     *
     * @param StoreTaskRequest $request
     * @param Project $project
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $this->authorize('create', [Task::class, $project]);

        $task = $this->taskService->create(
            $project,
            $request->validated()
        );

        // Event is automatically dispatched via model's $dispatchesEvents
        // which triggers SendTaskNotification job

        return self::success(
            'Task created successfully. Notification sent to assigned user.',
            201,
            TaskResource::make($task->load(['project', 'assignedUser']))
        );
    }

    /**
     * Display the specified task
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return self::success(
            'Task retrieved successfully.',
            data: TaskResource::make($task->load(['project', 'assignedUser']))
        );
    }

    /**
     * Update the specified task
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updatedTask = $this->taskService->update(
            $task,
            $request->validated()
        );

        return self::success(
            'Task updated successfully.',
            data: TaskResource::make($updatedTask->load(['project', 'assignedUser']))
        );
    }

    /**
     * Remove the specified task
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return self::success('Task deleted successfully.');
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
