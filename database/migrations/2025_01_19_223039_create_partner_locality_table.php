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
        Schema::create('partner_locality', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->foreignId('locality_id')->constrained('localities')->onDelete('cascade'); // O puede ser 'provinces' o 'regions', dependiendo de cómo las manejas

            // Aseguramos que no haya dos partners asignados a la misma localidad
            $table->unique(['partner_id', 'locality_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_locality');
    }
};
