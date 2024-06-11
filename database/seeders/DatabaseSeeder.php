<?php

use App\Models\Consultation;
use App\Models\DoctorSpecialization;
use App\Models\Message;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class {
    public function run(): void
    {
        $patient = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PATIENT,
        ]);

        $doctor = User::create([
            'name' => 'Dr. Smith',
            'email' => 'drsmith@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DOCTOR,
        ]);

        User::create([
            'name' => 'City Hospital',
            'email' => 'hospital@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_HOSPITAL,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $specialization = Specialization::create([
            'name' => 'Cardiology',
        ]);

        DoctorSpecialization::create([
            'doctor_id' => $doctor->id,
            'specialization_id' => $specialization->id,
        ]);

        $consultation = Consultation::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'specialization_id' => $specialization->id,
            'type' => 'specialization',
            'status' => 'in_progress',
        ]);

        Message::create([
            'consultation_id' => $consultation->id,
            'sender_id' => $patient->id,
            'message' => 'Hello, doctor!',
        ]);

        Message::create([
            'consultation_id' => $consultation->id,
            'sender_id' => $doctor->id,
            'message' => 'Hello, how can I help you?',
        ]);
    }
};
