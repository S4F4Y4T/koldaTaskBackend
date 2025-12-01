<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            foreach (PermissionEnum::modules() as $moduleName => $permissions) {
                $module = Module::firstOrCreate(['name' => $moduleName]);

                foreach ($permissions as $permissionEnum) {
                    Permission::firstOrCreate(
                        ['name' => $permissionEnum->value],
                        ['module_id' => $module->id]
                    );
                }
            }
        });
    }
}
