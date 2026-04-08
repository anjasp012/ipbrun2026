<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@ipbrun.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // 2. Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@ipbrun.com'],
            [
                'name' => 'Admin Staff',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 3. PIC
        \App\Models\User::updateOrCreate(
            ['email' => 'pic@ipbrun.com'],
            [
                'name' => 'PIC Field',
                'username' => 'pic',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'pic',
            ]
        );
    }
}
