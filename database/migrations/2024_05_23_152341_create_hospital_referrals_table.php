<?php

use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::schema()->create('hospital_referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('consultation_id');
            $table->uuid('hospital_id');
            $table->text('referral_notes');
            $table->timestamps();

            $table->foreign('consultation_id')->references('id')->on('consultations');
            $table->foreign('hospital_id')->references('id')->on('hospitals');
        });
    }

    public function down(): void
    {
        Schema::schema()->dropIfExists('hospital_referrals');
    }
};
