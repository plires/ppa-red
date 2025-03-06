<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FormSubmission;
use Illuminate\Console\Command;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendFormStatusChangeToPartner;

class UpdateFormSubmissionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
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

        // Definir los nombres de los estados que necesitas
        $statuses = [
            FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
            FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
            FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
        ];

        // Obtener todos los IDs en una sola consulta
        $statusIds = FormSubmissionStatus::whereIn('status', $statuses)
            ->pluck('id', 'status'); // Devuelve un array asociativo [ 'status_name' => id ]

        // Asignar las variables usando los valores obtenidos
        $pendingPartnerId = $statusIds[FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER] ?? null;
        $answeredPartnerId = $statusIds[FormSubmissionStatus::STATUS_RESPONDIO_PARTNER] ?? null;
        $delayedPartnerId = $statusIds[FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER] ?? null;
        $closedNoReplyPartnerId = $statusIds[FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER] ?? null;
        $closedNoReplyUserId = $statusIds[FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO] ?? null;

        // Buscar FormSubmissions con estado "Pendiente de Respuesta Del Partner" y sin cambios en 48h
        $submissions_pendingPartnerStatus = FormSubmission::where('form_submission_status_id', $pendingPartnerId)
            ->where('updated_at', '<', $now->subHours(48))
            ->get();

        if ($submissions_pendingPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_pendingPartnerStatus, $delayedPartnerId, 'te atrasaste 48 Hs', 'explicacion de porque te atrasaste 48hs');
        }

        // Buscar FormSubmissions "Demorado de rta del Partner" sin actividad en 7 días
        $submissions_delayedPartnerStatus = FormSubmission::where('form_submission_status_id', $delayedPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->get();

        if ($submissions_delayedPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_delayedPartnerStatus, $closedNoReplyPartnerId, 'Cerramos el ticket por falta de actividad en rta del partner', 'explicacion de porque te se cierra el ticket por falta de respuesta del partner');
        }

        // Buscar FormSubmissions "Respondido Por El Partner" sin actividad en 7 días por parte del usuario
        $submissions_answeredByPartner = FormSubmission::where('form_submission_status_id', $answeredPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->get();

        if ($submissions_answeredByPartner->isNotEmpty()) {
            $this->makeUpdates($submissions_answeredByPartner, $closedNoReplyUserId, 'Cerramos el ticket por falta de actividad en rta del ususario', 'explicacion de porque te se cierra el ticket por falta de respuesta del ususario');
        }

        $this->info('Estados de FormSubmissions actualizados correctamente.');
    }

    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->hourly();
    }

    public function makeUpdates($array, $statusId, $subject, $msg)
    {
        foreach ($array as $formSubmission) {
            $partner = $formSubmission->user;
            $dataUser = json_decode($formSubmission->data, true); // Convierte JSON en array

            if ($partner && $partner->email) {
                $this->sendEmailWithChangesToPartner($partner, $dataUser, $formSubmission, $subject, $msg);
            }

            $this->updateFormSubmissionStatus($formSubmission, $statusId);
        }
    }

    public function updateFormSubmissionStatus($formSubmission, $statusId)
    {
        // Actualizar el estado del FormSubmission
        $formSubmission->update(['form_submission_status_id' => $statusId]);
    }

    public function sendEmailWithChangesToPartner($partner, $dataUser, $formSubmission, $subject, $msg)
    {
        // Enviar el correo usando un Job
        SendFormStatusChangeToPartner::dispatch($partner, $dataUser, $formSubmission, $subject, $msg)->delay(now()->addSeconds(10));
    }
}
