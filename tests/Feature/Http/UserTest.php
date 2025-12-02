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
