<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@lpg.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
            'status_aktif' => true,
        ]);

        User::create([
            'name' => 'Kasir 1',
            'username' => 'kasir1',
            'email' => 'kasir1@lpg.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status_aktif' => true,
        ]);

        User::create([
            'name' => 'Owner',
            'username' => 'Owner',
            'email' => 'owner@lpg.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status_aktif' => true,
        ]);
    }
}
