<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // or Hash::make()
            ]
        );

        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        }


        $users = User::factory(5)->create();

        foreach ($users as $user) {
            $user->roles()->sync([1, 2, 3]);
            $user->permissions()->sync([1, 2, 3]);
        }
    }
}
