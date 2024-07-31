<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'user-management']);
        Permission::create(['name' => 'import-orders']);
        Permission::create(['name' => 'import-customer-data']);

        // Create role and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
