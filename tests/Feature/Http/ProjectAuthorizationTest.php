<?php

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->role = Role::factory()->create(['name' => 'Project Manager']);
    $this->user->roles()->attach($this->role);

    // Create module for permissions
    $this->module = \App\Models\Module::factory()->create(['name' => 'Project Management']);
});

it('allows user with PROJECT_READ permission to view all projects', function () {
    $permission = Permission::create(['name' => PermissionEnum::PROJECT_READ->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    Project::factory()->count(3)->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.projects.index'));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data'))->toHaveCount(3);
});

it('denies user without PROJECT_READ permission from viewing projects', function () {
    Project::factory()->count(3)->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.projects.index'));

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with PROJECT_CREATE permission to create a project', function () {
    $permission = Permission::create(['name' => PermissionEnum::PROJECT_CREATE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $token = auth()->login($this->user);

    $projectData = [
        'title' => 'New Project',
        'client' => 'Test Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => 'pending',
    ];

    $response = $this->withToken($token)->postJson(route('v1.projects.store'), $projectData);

    expect($response->status())->toBe(201)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('New Project');
});

it('denies user without PROJECT_CREATE permission from creating a project', function () {
    $token = auth()->login($this->user);

    $projectData = [
        'title' => 'New Project',
        'client' => 'Test Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => 'pending',
    ];

    $response = $this->withToken($token)->postJson(route('v1.projects.store'), $projectData);

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with PROJECT_UPDATE permission to update a project', function () {
    $permission = Permission::create(['name' => PermissionEnum::PROJECT_UPDATE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $project = Project::factory()->create();

    $token = auth()->login($this->user);

    $updateData = [
        'title' => 'Updated Project Title',
        'status' => 'in_progress',
    ];

    $response = $this->withToken($token)->putJson(route('v1.projects.update', $project), $updateData);

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('Updated Project Title');
});

it('denies user without PROJECT_UPDATE permission from updating a project', function () {
    $project = Project::factory()->create();

    $token = auth()->login($this->user);

    $updateData = [
        'title' => 'Updated Project Title',
    ];

    $response = $this->withToken($token)->putJson(route('v1.projects.update', $project), $updateData);

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with PROJECT_DELETE permission to delete a project', function () {
    $permission = Permission::create(['name' => PermissionEnum::PROJECT_DELETE->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $project = Project::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->deleteJson(route('v1.projects.destroy', $project));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success');

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

it('denies user without PROJECT_DELETE permission from deleting a project', function () {
    $project = Project::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->deleteJson(route('v1.projects.destroy', $project));

    expect($response->status())->toBe(403)
        ->and($response->json('type'))->toBe('error');
});

it('allows user with PROJECT_READ permission to view a single project', function () {
    $permission = Permission::create(['name' => PermissionEnum::PROJECT_READ->value, 'module_id' => $this->module->id]);
    $this->role->permissions()->attach($permission);

    $project = Project::factory()->create();

    $token = auth()->login($this->user);

    $response = $this->withToken($token)->getJson(route('v1.projects.show', $project));

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.id'))->toBe($project->id);
});

it('allows user with multiple permissions to perform multiple actions', function () {
    $readPermission = Permission::create(['name' => PermissionEnum::PROJECT_READ->value, 'module_id' => $this->module->id]);
    $createPermission = Permission::create(['name' => PermissionEnum::PROJECT_CREATE->value, 'module_id' => $this->module->id]);
    $updatePermission = Permission::create(['name' => PermissionEnum::PROJECT_UPDATE->value, 'module_id' => $this->module->id]);

    $this->role->permissions()->attach([$readPermission->id, $createPermission->id, $updatePermission->id]);

    $token = auth()->login($this->user);

    // Can read
    $readResponse = $this->withToken($token)->getJson(route('v1.projects.index'));
    expect($readResponse->status())->toBe(200);

    // Can create
    $createResponse = $this->withToken($token)->postJson(route('v1.projects.store'), [
        'title' => 'Test Project',
        'client' => 'Test Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => 'pending',
    ]);
    expect($createResponse->status())->toBe(201);

    // Can update
    $project = Project::factory()->create();
    $updateResponse = $this->withToken($token)->putJson(route('v1.projects.update', $project), [
        'title' => 'Updated Title',
    ]);
    expect($updateResponse->status())->toBe(200);

    // Cannot delete (no permission)
    $deleteResponse = $this->withToken($token)->deleteJson(route('v1.projects.destroy', $project));
    expect($deleteResponse->status())->toBe(403);
});
