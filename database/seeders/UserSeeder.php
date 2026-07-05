<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
public function run(): void
{
    // Admin
    User::updateOrCreate(
        ['email' => 'admin@mail.com'],
        [
            'name'     => 'Owner',
            'password' => Hash::make('123'),
            'role'     => 'admin',
        ]
    );

    // Kasir
    User::updateOrCreate(
        ['email' => 'kasir@mail.com'],
        [
            'name'     => 'Kasir',
            'password' => Hash::make('123'),
            'role'     => 'kasir',
        ]
    );
}}