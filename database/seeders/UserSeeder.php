<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'division_id' => null,
            'phone_number' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'domicile' => 'Jakarta',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
        ]);

        // Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'division_id' => null,
            'phone_number' => '081234567891',
            'address' => 'Jl. Manager No. 2',
            'domicile' => 'Bandung',
            'date_of_birth' => '1985-02-02',
            'gender' => 'female',
        ]);

        // Employee
        User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'division_id' => null,
            'phone_number' => '081234567892',
            'address' => 'Jl. Employee No. 3',
            'domicile' => 'Surabaya',
            'date_of_birth' => '1995-03-03',
            'gender' => 'other',
        ]);
    }
}
