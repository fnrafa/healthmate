<?php

namespace App\Controllers;

use App\Models\Consultation;
use App\Models\User;

class DashboardController
{
    public function index(): string
    {
        $user = auth();

        if (!$user) {
            return view('login', showAlert(403, 'You must be logged in to access this page'));
        }

        return match ($user->role) {
            'doctor' => view('doctor.index'),
            'hospital' => view('hospital.index'),
            'admin' => $this->admin(),
            default => $this->patient($user->id),
        };
    }

    public function admin(): string
    {
        $users = User::all();
        return view('admin.index', ['users' => $users]);
    }

    public function patient($id): string
    {
        $consults = Consultation::with(['patient', 'doctor'])
            ->where('patient_id', $id)
            ->get();
        return view('index', ['consults' => $consults]);
    }
}
