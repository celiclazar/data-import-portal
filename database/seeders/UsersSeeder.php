<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('test123'),
        ]);
        $adminUser->assignRole('admin');

        User::create([
            'name' => 'Testing User',
            'email' => 'testing@test.com',
            'password' => Hash::make('test1234'),
        ]);
    }
}
