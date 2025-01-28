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
            $table->foreignId('partner_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade'); // Puede ser CABA en lugar de una provincia normal
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null'); // Nulo si es CABA
            $table->foreignId('locality_id')->constrained('localities')->onDelete('cascade');
            $table->json('data'); // Datos del formulario en formato JSON
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
