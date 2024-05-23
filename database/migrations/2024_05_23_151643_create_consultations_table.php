<?php

use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::schema()->create('consultations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('doctor_id');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });
    }

    public function down(): void
    {
        Schema::schema()->dropIfExists('consultations');
    }
};
