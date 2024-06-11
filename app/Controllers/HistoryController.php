<?php

namespace App\Controllers;

use App\Models\Consultation;
use JetBrains\PhpStorm\NoReturn;

class HistoryController
{
    private string $sidebar = 'history';

    public function index(): ?string
    {
        $user = auth();

        if (!$user) {
            showAlert(403, 'You must be logged in to access this page');
            redirect('/login');
        }

        if ($user->role == 'admin') {
            $this->admin();
        }

        return match ($user->role) {
            'doctor' => $this->doctor($user->id),
            'hospital' => $this->hospital($user->id),
            default => $this->patient($user->id),
        };
    }

    #[NoReturn] public function admin(): void
    {
        showAlert(404, 'Redirecting, You not able to access this page');
        redirect('/');
    }

    public function doctor($id): string
    {
        $consults = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('doctor_id', $id)
            ->whereIn('status', ['in_progress', 'referred', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('doctor.history', [
            'sidebar' => $this->sidebar,
            'consults' => $consults
        ]);
    }

    public function patient($id): string
    {
        $consults = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('patient_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('history', [
            'sidebar' => $this->sidebar,
            'consults' => $consults
        ]);
    }

    private function hospital($id): string
    {
        return view('hospital.history', [
            'sidebar' => $this->sidebar
        ]);
    }
}