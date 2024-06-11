<?php

namespace App\Controllers;

use App\Models\Consultation;
use JetBrains\PhpStorm\NoReturn;

class RequestController
{
    private string $sidebar = 'request';

    public function index(): ?string
    {
        $user = auth();

        if (!$user) {
            showAlert(403, 'You must be logged in to access this page');
            redirect('/login');
        }

        if ($user->role !== 'doctor') {
            $this->unauthorized();
        } else {
            return $this->doctor();
        }
    }

    #[NoReturn] public function unauthorized(): void
    {
        showAlert(404, 'Redirecting, You not able to access this page');
        redirect('/');
    }

    public function doctor(): string
    {
        $consults = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('status', ['requested'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('doctor.request', [
            'sidebar' => $this->sidebar,
            'consults' => $consults
        ]);
    }
}