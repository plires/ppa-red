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
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade'); // FK hacia provincias
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade'); // FK hacia regiones
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('cascade'); // FK hacia zonas
            $table->string('type'); // Tipo de localidad: 'ciudad', 'barrio', 'departamento'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
