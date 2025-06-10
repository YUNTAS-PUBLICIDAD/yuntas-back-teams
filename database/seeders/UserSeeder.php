<?php

namespace Database\Seeders;
// database/seeders/AdminUserSeeder.php
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'celular' => '999999991',
                'password' => Hash::make('admin'),
            ]
        );
    }
}
