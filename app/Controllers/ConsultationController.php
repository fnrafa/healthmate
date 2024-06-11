<?php

namespace App\Controllers;

use App\Models\Consultation;
use App\Models\Message;
use App\Providers\Request;
use JetBrains\PhpStorm\NoReturn;

class ConsultationController
{
    private string $sidebar = 'consultation';

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
            'hospital' => view('hospital.index'),
            default => $this->patient($user->id),
        };
    }

    #[NoReturn] public function update(Request $request): void
    {
        if (auth()->role != 'doctor') {
            response(['success' => false, 'message' => "You can't access this page"], 403);
        } else {
            
            $request->validate([
                'id' => 'required|exists:consultations,id',
                'status' => 'required|string',
            ]);

            $consultation = Consultation::find($request->id);

            $consultation->status = $request->status;
            $consultation->doctor_id = auth()->id;
            $consultation->save();

            response(['success' => true, 'message' => 'Consultation status updated successfully']);
        }
    }

    #[NoReturn] public function admin(): void
    {
        showAlert(404, 'Redirecting, You not able to access this page');
        redirect('/');
    }


    public function patient($id): string
    {
        $consult = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('patient_id', $id)
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($consult) {
            $messages = Message::where('consultation_id', $consult->id)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $messages = null;
        }

        return view('consultation', [
            'sidebar' => $this->sidebar,
            'consult' => $consult,
            'messages' => $messages
        ]);
    }

    public function doctor($id): string
    {
        $consult = Consultation::with(['patient', 'doctor', 'specialization'])
            ->where('doctor_id', $id)
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($consult) {
            $messages = Message::where('consultation_id', $consult->id)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $messages = null;
        }
        return view('doctor.consultation', [
            'sidebar' => $this->sidebar,
            'consult' => $consult,
            'messages' => $messages
        ]);
    }

    #[NoReturn] public function requestConsultation(Request $request): string
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'type' => 'required|in:free,specialization,specific_doctor',
            'specialization_id' => 'required_if:type,specialization|exists:specializations,id',
            'doctor_id' => 'required_if:type,specific_doctor|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $consultationData = [
            'patient_id' => $request->patient_id,
            'type' => $request->type,
            'notes' => $request->notes,
            'status' => 'requested',
        ];

        if ($request->type === 'specialization') {
            $consultationData['specialization_id'] = $request->specialization_id;
        }

        if ($request->type === 'specific_doctor') {
            $consultationData['doctor_id'] = $request->doctor_id;
        }

        $consultation = Consultation::create($consultationData);

        response(['success' => true, 'message' => 'Consultation requested successfully', 'consultation_id' => $consultation->id]);
    }
}