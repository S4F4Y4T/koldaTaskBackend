<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $admin = Role::query()->updateOrCreate(['name' => 'admin']);

    $permissionIds = Permission::pluck('id')->toArray();

    $admin->permissions()->sync($permissionIds);

    // Create random roles and assign first 3 permissions
    $roles = Role::factory(5)->create();
    foreach ($roles as $role) {
        $role->permissions()->sync([1, 2, 3]);
    }
}
}
