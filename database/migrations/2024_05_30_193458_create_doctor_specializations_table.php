<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Capsule::schema()->create('doctor_specializations', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id');
            $table->unsignedInteger('specialization_id');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialization_id')->references('id')->on('specializations')->onDelete('cascade');
            $table->primary(['doctor_id', 'specialization_id']);
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('doctor_specializations');
    }
};
