<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FormSubmissionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_submission_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('name', [
                FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER
            ])->default(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER); // Diferentes estados posibles
            $table->text('description')->nullable();
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
