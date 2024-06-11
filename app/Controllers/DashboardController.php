<?php

namespace App\Controllers;

use App\Models\Consultation;
use App\Models\Specialization;
use App\Models\User;

class DashboardController
{
    private string $sidebar = 'dashboard';

    public function index(): string
    {
        $user = auth();

        if (!$user) {
            showAlert(403, 'You must be logged in to access this page');
            redirect('/login');
        }

        return match ($user->role) {
            'doctor' => $this->doctor(),
            'hospital' => view('hospital.index'),
            'admin' => $this->admin(),
            default => $this->patient($user->id),
        };
    }

    public function doctor(): string
    {
        return view('doctor.index', ['sidebar' => $this->sidebar,]);
    }

    public function admin(): string
    {
        $users = User::all();
        return view('admin.index', ['users' => $users]);
    }

    public function patient($id): string
    {
        $consult = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('patient_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $statusCheck = true;
        if ($consult && $consult->status !== 'completed') {
            $statusCheck = false;
        }
        $specializations = Specialization::all();
        $doctors = User::where('role', "doctor")->get();
        return view('index', [
            'sidebar' => $this->sidebar,
            'consult' => $consult,
            'statusCheck' => $statusCheck,
            'specializations' => $specializations,
            'doctors' => $doctors
        ]);
    }
}
