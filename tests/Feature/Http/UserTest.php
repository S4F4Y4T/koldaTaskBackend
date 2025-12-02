<?php

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->role = Role::factory()->create(['name' => 'Admin']);
    $this->user->roles()->attach($this->role);
    
    // Create module for permissions
    $module = \App\Models\Module::factory()->create(['name' => 'User Management']);
    
    // Grant all user permissions
    $permissions = [
        Permission::create(['name' => PermissionEnum::USER_READ->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::USER_CREATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::USER_UPDATE->value, 'module_id' => $module->id]),
        Permission::create(['name' => PermissionEnum::USER_DELETE->value, 'module_id' => $module->id]),
    ];
    
    $this->role->permissions()->attach(array_column($permissions, 'id'));
    $this->token = auth()->login($this->user);
});

it('retrieves all users successfully', function () {
    User::factory()->count(5)->create();
    
    $response = $this->withToken($this->token)->getJson(route('v1.users.index'));
    
    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'links', 'meta']);
});

it('filters users by name', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Doe']);
    
    $response = $this->withToken($this->token)->getJson(route('v1.users.index', ['name' => 'John']));
    
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

it('assigns roles when creating a user', function () {
    $role = Role::factory()->create(['name' => 'Editor']);
    
    $response = $this->withToken($this->token)->postJson(route('v1.users.store'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'role_ids' => [$role->id],
    ]);
    
    $response->assertStatus(201)
        ->assertJsonPath('data.roles.0.id', $role->id);
    
    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->roles->contains($role))->toBeTrue();
});

it('syncs roles when updating a user', function () {
    $user = User::factory()->create();
    $role1 = Role::factory()->create(['name' => 'Role 1']);
    $role2 = Role::factory()->create(['name' => 'Role 2']);
    
    $user->roles()->attach($role1);
    
    $response = $this->withToken($this->token)->putJson(route('v1.users.update', $user), [
        'name' => 'Updated Name',
        'email' => $user->email,
        'role_ids' => [$role2->id],
    ]);
    
    $response->assertStatus(200)
        ->assertJsonPath('data.roles.0.id', $role2->id);
    
    $user->refresh();
    expect($user->roles->contains($role1))->toBeFalse();
    expect($user->roles->contains($role2))->toBeTrue();
});
