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
        Schema::create('form_submission_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_submission_id');
            $table->unsignedBigInteger('previous_status_id')->nullable();
            $table->unsignedBigInteger('new_status_id');
            $table->string('closure_reason')->nullable();
            $table->text('notification_details')->nullable();
            $table->boolean('is_read')->default(false); // Nuevo campo
            $table->timestamp('read_at')->nullable(); // Opcionalmente, añadir cuándo fue leída
            $table->timestamps();

            $table->foreign('form_submission_id')->references('id')->on('form_submissions');
            $table->foreign('previous_status_id')->references('id')->on('form_submission_statuses');
            $table->foreign('new_status_id')->references('id')->on('form_submission_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission_notifications');
    }
};
