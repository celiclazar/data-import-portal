<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permission = Permission::create(['name' => 'user-management']);
        $role = Role::create(['name' => 'admin']);

        $role->givePermissionTo($permission);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('test123'), // Change this to a secure password
        ]);

        $user->assignRole('admin');
    }
}
