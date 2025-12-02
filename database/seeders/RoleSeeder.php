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
        // admin role for all permissions
        $admin = Role::query()->updateOrCreate(['name' => 'admin']);
        $permissions = Permission::all()->pluck('id')->toArray();
        $admin->assignPermission($permissions);

        // random role for first 5 permissions
        $roles = Role::factory(5)->create();
        foreach ($roles as $role) {
            $role->permissions()->sync([1, 2, 3]);
        }
    }
}
