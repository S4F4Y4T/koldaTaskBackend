<?php

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->role = Role::factory()->create(['name' => 'Task Manager']);
    $this->user->roles()->attach($this->role);

    // Create module for permissions
    $this->module = \App\Models\Module::factory()->create(['name' => 'Task Management']);

    $this->project = Project::factory()->create();
});

it('allows user with TASK_READ permission to view all tasks', function () {
    $permission = Permission::create(['name' => PermissionEnum::TASK_READ->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    Task::factory()->count(5)->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.tasks.index'));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data'))->toHaveCount(5);
});

it('denies user without TASK_READ permission from viewing tasks', function () {
    Task::factory()->count(3)->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.tasks.index'));

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with TASK_CREATE permission to create a task', function () {
    $permission = Permission::create(['name' => PermissionEnum::TASK_CREATE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $token = auth()->login($this->user);

    $taskData = [
        'title' => 'New Task',
        'description' => 'Task description',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'assigned_user_id' => $this->user->id,
        'status' => 'pending',
    ];

    $response = $this->withToken($token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );

    expect($response->status())->toBe(201)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('New Task');
});

it('denies user without TASK_CREATE permission from creating a task', function () {
    $token = auth()->login($this->user);

    $taskData = [
        'title' => 'New Task',
        'description' => 'Task description',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'assigned_user_id' => $this->user->id,
        'status' => 'pending',
    ];

    $response = $this->withToken($token)->postJson(
        route('v1.projects.tasks.store', $this->project),
        $taskData
    );

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with TASK_UPDATE permission to update a task', function () {
    $permission = Permission::create(['name' => PermissionEnum::TASK_UPDATE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $task = Task::factory()->create(['project_id' => $this->project->id]);

    $token = auth()->login($this->user);

    $updateData = [
        'title' => 'Updated Task Title',
        'status' => 'in_progress',
    ];

    $response = $this->withToken($token)->putJson(route('v1.tasks.update', $task), $updateData);

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('Updated Task Title');
});

it('denies user without TASK_UPDATE permission from updating a task', function () {
    $task = Task::factory()->create();

    $token = auth()->login($this->user);

    $updateData = [
        'title' => 'Updated Task Title',
    ];

    $response = $this->withToken($token)->putJson(route('v1.tasks.update', $task), $updateData);

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with TASK_DELETE permission to delete a task', function () {
    $permission = Permission::create(['name' => PermissionEnum::TASK_DELETE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $task = Task::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->deleteJson(route('v1.tasks.destroy', $task));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success');

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('denies user without TASK_DELETE permission from deleting a task', function () {
    $task = Task::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->deleteJson(route('v1.tasks.destroy', $task));

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with TASK_READ permission to view a single task', function () {
    $permission = Permission::create(['name' => PermissionEnum::TASK_READ->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $task = Task::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.tasks.show', $task));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.id'))->toBe($task->id);
});

it('allows user with multiple task permissions to perform multiple actions', function () {
    $readPermission = Permission::create(['name' => PermissionEnum::TASK_READ->value, 'module_id' => $this->module->id]);
    $createPermission = Permission::create(['name' => PermissionEnum::TASK_CREATE->value, 'module_id' => $this->module->id]);
    $updatePermission = Permission::create(['name' => PermissionEnum::TASK_UPDATE->value, 'module_id' => $this->module->id]);

    $this->role->permissions()->attach([$readPermission->id, $createPermission->id, $updatePermission->id]);

    $token = auth()->login($this->user);

    // Can read
    $readResponse = $this->withToken($token)->getJson(route('v1.tasks.index'));
    expect($readResponse->status())->toBe(200);

    // Can create
    $createResponse = $this->withToken($token)->postJson(route('v1.projects.tasks.store', $this->project), [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'assigned_user_id' => $this->user->id,
        'status' => 'pending',
    ]);
    expect($createResponse->status())->toBe(201);

    // Can update
    $task = Task::factory()->create();
    $updateResponse = $this->withToken($token)->putJson(route('v1.tasks.update', $task), [
        'title' => 'Updated Title',
    ]);
    expect($updateResponse->status())->toBe(200);

    // Cannot delete (no permission)
    $deleteResponse = $this->withToken($token)->deleteJson(route('v1.tasks.destroy', $task));
    expect($deleteResponse->status())->toBe(403);
});
