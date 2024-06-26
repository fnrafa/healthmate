<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Capsule::schema()->create('hospital_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consultation_id');
            $table->unsignedInteger('hospital_id');
            $table->text('note');
            $table->dateTime('appointment_time');
            $table->timestamps();

            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
            $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('hospital_notes');
    }
};
