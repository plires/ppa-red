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

        // Obtener IDs de los estados para evitar hardcodear valores
        $pendientePartnerId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
        $respondidoPartnerId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
        $demoradoPartnerId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER);
        $cerradoSinRtaPartnerId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER);
        $cerradoSinRtaUsuarioId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO);
        $cerradoSinActividadId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER);

        // Buscar FormSubmissions con estado "Pendiente de Respuesta Del Partner" y sin cambios en 48h
        FormSubmission::where('form_submission_status_id', $pendientePartnerId)
            ->where('updated_at', '<', $now->subHours(48))
            ->update(['form_submission_status_id' => $demoradoPartnerId]);

        // Buscar FormSubmissions "Demorado" sin respuesta del partner en 7 días
        FormSubmission::whereIn('form_submission_status_id', [$demoradoPartnerId])
            ->where('updated_at', '<', $now->subDays(7))
            ->whereDoesntHave('formResponses', function ($query) {
                $query->where('is_system', true); // Filtrar solo respuestas del partner
            })
            ->update(['form_submission_status_id' => $cerradoSinRtaPartnerId]);

        // Buscar FormSubmissions "Respondido por el partner" sin actividad en 7 días
        FormSubmission::where('form_submission_status_id', $respondidoPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->get()
            ->each(function ($formSubmission) use ($cerradoSinRtaUsuarioId, $cerradoSinActividadId) {
                $userMessageCount = $formSubmission->formResponses()
                    ->where('is_system', false) // Filtrar solo respuestas del usuario
                    ->count();

                if ((int)$userMessageCount === 1) {
                    $formSubmission->update(['form_submission_status_id' => $cerradoSinRtaUsuarioId]);
                } elseif ($userMessageCount > 1) {
                    $formSubmission->update(['form_submission_status_id' => $cerradoSinActividadId]);
                }
            });

        $this->info('Estados de FormSubmissions actualizados correctamente.');
    }

    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->hourly();
    }
}
