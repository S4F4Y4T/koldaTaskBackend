<?php

namespace Database\Seeders;


use App\Models\AnimalPart;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModulePermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
