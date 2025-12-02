<?php

use App\Enums\PermissionEnum;
use App\Enums\ProjectStatus;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->role = Role::factory()->create(['name' => 'Admin']);
    $this->user->roles()->attach($this->role);
    
    // Create module for permissions
    $module = \App\Models\Module::factory()->create(['name' => 'Project Management']);
    
    // Grant all project permissions
    $permissions = [
        Permission::create(['name' => PermissionEnum::PROJECT_READ->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::PROJECT_CREATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::PROJECT_UPDATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::PROJECT_DELETE->value, 'module_id' => $module->id]),
    ];
    
    $this->role->permissions()->attach(array_column($permissions, 'id'));
    $this->token = auth()->login($this->user);
});

it('retrieves all projects successfully', function () {
    Project::factory()->count(5)->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.projects.index'));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data'))->toHaveCount(5);
});

it('retrieves a single project successfully', function () {
    $project = Project::factory()->create([
        'title' => 'Test Project',
        'client' => 'Test Client',
    ]);
    
    $response = $this->withToken($this->token)->getJson(route('v1.projects.show', $project));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.id'))->toBe($project->id)
        ->and($response->json('data.title'))->toBe('Test Project')
        ->and($response->json('data.client'))->toBe('Test Client');
});

it('creates a project successfully', function () {
    $projectData = [
        'title' => 'New Project',
        'client' => 'New Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => ProjectStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(route('v1.projects.store'), $projectData);
    
    expect($response->status())->toBe(201)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('New Project')
        ->and($response->json('data.client'))->toBe('New Client')
        ->and($response->json('data.status'))->toBe(ProjectStatus::PENDING->value);
    
    $this->assertDatabaseHas('projects', [
        'title' => 'New Project',
        'client' => 'New Client',
    ]);
});

it('validates required fields when creating a project', function () {
    $response = $this->withToken($this->token)->postJson(route('v1.projects.store'), []);
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKeys(['title', 'client', 'start_date', 'end_date', 'status']);
});

it('validates title is required', function () {
    $projectData = [
        'client' => 'Test Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => ProjectStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(route('v1.projects.store'), $projectData);
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('title');
});

it('validates status is a valid enum value', function () {
    $projectData = [
        'title' => 'Test Project',
        'client' => 'Test Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'status' => 'invalid_status',
    ];
    
    $response = $this->withToken($this->token)->postJson(route('v1.projects.store'), $projectData);
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('status');
});

it('updates a project successfully', function () {
    $project = Project::factory()->create([
        'title' => 'Original Title',
        'status' => ProjectStatus::PENDING->value,
    ]);
    
    $updateData = [
        'title' => 'Updated Title',
        'status' => ProjectStatus::IN_PROGRESS->value,
    ];
    
    $response = $this->withToken($this->token)->putJson(route('v1.projects.update', $project), $updateData);
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data.title'))->toBe('Updated Title')
        ->and($response->json('data.status'))->toBe(ProjectStatus::IN_PROGRESS->value);
    
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => 'Updated Title',
        'status' => ProjectStatus::IN_PROGRESS->value,
    ]);
});

it('partially updates a project', function () {
    $project = Project::factory()->create([
        'title' => 'Original Title',
        'client' => 'Original Client',
    ]);
    
    $updateData = [
        'title' => 'Updated Title Only',
    ];
    
    $response = $this->withToken($this->token)->putJson(route('v1.projects.update', $project), $updateData);
    
    expect($response->status())->toBe(200)
        ->and($response->json('data.title'))->toBe('Updated Title Only')
        ->and($response->json('data.client'))->toBe('Original Client');
});

it('deletes a project successfully', function () {
    $project = Project::factory()->create();
    
    $response = $this->withToken($this->token)->deleteJson(route('v1.projects.destroy', $project));
    
    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success');
    
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

it('returns 404 when trying to view non-existent project', function () {
    $response = $this->withToken($this->token)->getJson(route('v1.projects.show', 99999));
    
    expect($response->status())->toBe(404);
});

it('returns 404 when trying to update non-existent project', function () {
    $response = $this->withToken($this->token)->putJson(route('v1.projects.update', 99999), [
        'title' => 'Updated Title',
    ]);
    
    expect($response->status())->toBe(404);
});

it('returns 404 when trying to delete non-existent project', function () {
    $response = $this->withToken($this->token)->deleteJson(route('v1.projects.destroy', 99999));
    
    expect($response->status())->toBe(404);
});

it('includes related tasks when viewing a project', function () {
    $project = Project::factory()->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.projects.show', $project));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveKey('tasks');
});

it('filters projects by status', function () {
    Project::factory()->pending()->count(2)->create();
    Project::factory()->inProgress()->count(3)->create();
    Project::factory()->completed()->count(1)->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.projects.index', ['status' => 'in_progress']));
    
    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveCount(3);
});

it('validates end_date is after start_date', function () {
    $projectData = [
        'title' => 'Test Project',
        'client' => 'Test Client',
        'start_date' => now()->addDays(10)->format('Y-m-d'),
        'end_date' => now()->format('Y-m-d'),
        'status' => ProjectStatus::PENDING->value,
    ];
    
    $response = $this->withToken($this->token)->postJson(route('v1.projects.store'), $projectData);
    
    expect($response->status())->toBe(422)
        ->and($response->json('errors'))->toHaveKey('end_date');
});


