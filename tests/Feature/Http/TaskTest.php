<?php

use App\Enums\PermissionEnum;
use App\Enums\TaskStatus;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->role = Role::factory()->create(['name' => 'Admin']);
    $this->user->roles()->attach($this->role);
    
    // Create module for permissions
    $module = \App\Models\Module::factory()->create(['name' => 'Task Management']);
    
    // Grant all task permissions
    $permissions = [
        Permission::create(['name' => PermissionEnum::TASK_READ->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::TASK_CREATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::TASK_UPDATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::TASK_DELETE->value, 'module_id' => $module->id]),
    ];
    
    $this->role->permissions()->attach(array_column($permissions, 'id'));
    $this->token = auth()->login($this->user);
    
    $this->project = Project::factory()->create();
});

it('retrieves all tasks successfully', function () {
    Task::factory()->count(7)->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.index'));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data'))->toHaveCount(7);
});

it('retrieves a single task successfully', function () {
    $task = Task::factory()->create([
        'title' => 'Test Task',
        'description' => 'Test Description',
        'project_id' => $this->project->id,
    ]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.show', $task));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.id'))->toBe($task->id)
        ->and($response->json('data.title'))->toBe('Test Task')
        ->and($response->json('data.description'))->toBe('Test Description');
});

it('creates a task successfully', function () {
    $taskData = [
        'title' => 'New Task',
        'description' => 'Task description',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'assigned_user_id' => $this->user->id,
        'status' => TaskStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );
    
    expect($response->status())->toBe(201)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('New Task')
        ->and($response->json('data.description'))->toBe('Task description')
        ->and($response->json('data.status'))->toBe(TaskStatus::PENDING->value);
    
    $this->assertDatabaseHas('tasks', [
        'title' => 'New Task',
        'project_id' => $this->project->id,
    ]);
});

it('validates required fields when creating a task', function () {
    $response = $this->withToken($this->token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        []
    );
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKeys(['title', 'deadline', 'status']);
});

it('validates title is required', function () {
    $taskData = [
        'description' => 'Task description',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'status' => TaskStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('title');
});

it('validates status is a valid enum value', function () {
    $taskData = [
        'title' => 'Test Task',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'status' => 'invalid_status',
    ];
    
    $response = $this->withToken($this->token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('status');
});

it('validates assigned_user_id exists in users table', function () {
    $taskData = [
        'title' => 'Test Task',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'assigned_user_id' => 99999,
        'status' => TaskStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('assigned_user_id');
});

it('updates a task successfully', function () {
    $task = Task::factory()->create([
        'title' => 'Original Title',
        'status' => TaskStatus::PENDING->value,
        'project_id' => $this->project->id,
    ]);
    
    $updateData = [
        'title' => 'Updated Title',
        'status' => TaskStatus::IN_PROGRESS->value,
    ];
    
    $response = $this->withToken($this->token)->putJson(route('v1.tasks.update', $task), $updateData);
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('Updated Title')
        ->and($response->json('data.status'))->toBe(TaskStatus::IN_PROGRESS->value);
    
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Title',
        'status' => TaskStatus::IN_PROGRESS->value,
    ]);
});

it('partially updates a task', function () {
    $task = Task::factory()->create([
        'title' => 'Original Title',
        'description' => 'Original Description',
        'project_id' => $this->project->id,
    ]);
    
    $updateData = [
        'title' => 'Updated Title Only',
    ];
    
    $response = $this->withToken($this->token)->putJson(route('v1.tasks.update', $task), $updateData);
    
    expect($response->status())->toBe(200)
        ->and($response->json('data.title'))->toBe('Updated Title Only')
        ->and($response->json('data.description'))->toBe('Original Description');
});

it('deletes a task successfully', function () {
    $task = Task::factory()->create();
    
    $response = $this->withToken($this->token)->deleteJson(route('v1.tasks.destroy', $task));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success');
    
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('returns 404 when trying to view non-existent task', function () {
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.show', 99999));
    
    expect($response->status())->toBe(404);
});

it('returns 404 when trying to update non-existent task', function () {
    $response = $this->withToken($this->token)->putJson(route('v1.tasks.update', 99999), [
        'title' => 'Updated Title',
    ]);
    
    expect($response->status())->toBe(404);
});

it('returns 404 when trying to delete non-existent task', function () {
    $response = $this->withToken($this->token)->deleteJson(route('v1.tasks.destroy', 99999));
    
    expect($response->status())->toBe(404);
});

it('includes related project when viewing a task', function () {
    $task = Task::factory()->create(['project_id' => $this->project->id]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.show', $task));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveKey('project');
});

it('includes assigned user when viewing a task', function () {
    $assignedUser = User::factory()->create();
    $task = Task::factory()->create([
        'project_id' => $this->project->id,
        'assigned_user_id' => $assignedUser->id,
    ]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.show', $task));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveKey('assigned_user')
        ->and($response->json('data.assigned_user.id'))->toBe($assignedUser->id);
});

it('filters tasks by status', function () {
    Task::factory()->pending()->count(2)->create();
    Task::factory()->inProgress()->count(3)->create();
    Task::factory()->completed()->count(1)->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.index', ['status' => 'in_progress']));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveCount(3);
});

it('filters tasks by project', function () {
    $project1 = Project::factory()->create();
    $project2 = Project::factory()->create();
    
    Task::factory()->count(3)->create(['project_id' => $project1->id]);
    Task::factory()->count(2)->create(['project_id' => $project2->id]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.index', ['project_id' => $project1->id]));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveCount(3);
});

it('filters tasks by assigned user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    Task::factory()->count(4)->create(['assigned_user_id' => $user1->id]);
    Task::factory()->count(2)->create(['assigned_user_id' => $user2->id]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.tasks.index', ['assigned_user_id' => $user1->id]));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveCount(4);
});



it('can reassign a task to another user', function () {
    $originalUser = User::factory()->create();
    $newUser = User::factory()->create();
    
    $task = Task::factory()->create([
        'project_id' => $this->project->id,
        'assigned_user_id' => $originalUser->id,
    ]);
    
    $updateData = [
        'assigned_user_id' => $newUser->id,
    ];
    
    $response = $this->withToken($this->token)->putJson(route('v1.tasks.update', $task), $updateData);
    
    expect($response->status())->toBe(200)
        ->and($response->json('data.assigned_user.id'))->toBe($newUser->id);
    
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'assigned_user_id' => $newUser->id,
    ]);
});
