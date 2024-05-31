<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Capsule::schema()->create('consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('doctor_id')->nullable();
            $table->unsignedInteger('specialization_id')->nullable();
            $table->enum('type', ['free', 'specialization', 'specific_doctor'])->default('free');
            $table->text('notes')->nullable();
            $table->enum('status', ['requested', 'in_progress', 'referred', 'completed'])->default('requested');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('specialization_id')->references('id')->on('specializations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('consultations');
    }
};
