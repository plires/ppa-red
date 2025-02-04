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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->foreignId('locality_id')->constrained('localities')->onDelete('cascade');
            $table->json('data'); // Contiene nombre, email y teléfono, pero no los comentarios
            $table->foreignId('form_submission_status_id')->constrained('form_submission_statuses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
