<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FormSubmission;
use Illuminate\Console\Command;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Schedule;

class UpdateFormSubmissionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:update-form-submission-status';
    protected $signature = 'formsubmissions:update-status';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar automáticamente los estados de FormSubmissions según el tiempo transcurrido';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Obtener los IDs de los estados para evitar consultas repetitivas
        $pendingId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
        $delayedId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER);
        $closedByPartnerId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER);
        $respondedId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
        $closedByUserId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO);

        // 1️⃣ Cambiar a "Demorado - Sin Respuesta Del Partner (48h)" si han pasado más de 48 horas
        FormSubmission::where('form_submission_status_id', $pendingId)
            ->where('updated_at', '<=', $now->subHours(48))
            ->update(['form_submission_status_id' => $delayedId]);

        // 2️⃣ Cambiar a "Cerrado - Sin Respuesta Del Partner" si han pasado más de 7 días en estado "Demorado"
        FormSubmission::whereIn('form_submission_status_id', [$delayedId])
            ->where('updated_at', '<=', $now->subDays(7))
            ->update(['form_submission_status_id' => $closedByPartnerId]);

        // 3️⃣ Cambiar a "Cerrado - Sin Respuesta Del Usuario" si han pasado más de 7 días en estado "Respondido Por El Partner"
        FormSubmission::where('form_submission_status_id', $respondedId)
            ->where('updated_at', '<=', $now->subDays(7))
            ->update(['form_submission_status_id' => $closedByUserId]);

        $this->info('Estados de FormSubmissions actualizados correctamente.');
    }

    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->hourly();
    }
}
