<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_submission_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['en_curso', 'demorado', 'completo', 'rechazado', 'pendiente'])->default('en_curso'); // Diferentes estados posibles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission_statuses');
    }
};
