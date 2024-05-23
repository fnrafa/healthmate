<?php

use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::schema()->create('hospitals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address');
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::schema()->dropIfExists('hospitals');
    }
};
