<?php

namespace App\Controllers;

use App\Middlewares\Auth;

class DashboardController
{
    public function index(): string
    {
        $user = Auth::user();

        if (!$user) {
            return view('login', ['error' => 'You must be logged in to access this page']);
        }

        return match ($user->role) {
            'doctor' => view('doctor.index'),
            'hospital' => view('hospital.index'),
            default => view('index'),
        };
    }
}
