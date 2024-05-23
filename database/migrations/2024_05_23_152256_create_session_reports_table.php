<?php

use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::schema()->create('session_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('consultation_id');
            $table->text('diagnosis');
            $table->text('prescriptions');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('consultation_id')->references('id')->on('consultations');
        });
    }

    public function down(): void
    {
        Schema::schema()->dropIfExists('session_reports');
    }
};
