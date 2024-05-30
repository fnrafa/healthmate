<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class {
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PATIENT,
        ]);

        User::create([
            'name' => 'Dr. Smith',
            'email' => 'drsmith@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_DOCTOR,
        ]);

        User::create([
            'name' => 'City Hospital',
            'email' => 'hospital@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_HOSPITAL,
        ]);
    }
};
