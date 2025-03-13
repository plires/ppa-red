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
        Schema::create('transactional_emails', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type'); // 'partner' o 'user'
            $table->string('title')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->string('type'); // 'cambio de estado', 'notificacion', 'recordatorio'
            $table->string('variant')->nullable(); // 'nunca_respondio', 'respondio_antes'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactional_emails');
    }
};
