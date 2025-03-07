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

        // Buscar FormSubmissions con estado "Pendiente de Respuesta Del Partner" y sin actividad en 48h -> STATUS_DEMORADO_POR_PARTNER
        $now = Carbon::now();
        $submissions_pendingPartnerStatus = FormSubmission::where('form_submission_status_id', $pendingPartnerId)
            ->where('updated_at', '<', $now->subHours(48))
            ->get();

        if ($submissions_pendingPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_pendingPartnerStatus, $delayedPartnerId, 'te atrasaste 48 Hs', 'explicacion de porque te atrasaste 48hs');
        }

        // Buscar FormSubmissions "Demorado de rta del Partner" sin actividad en 7 días y que no tenga ni una respuesta del partner -> STATUS_CERRADO_SIN_RTA_PARTNER
        $now = Carbon::now();
        $submissions_sin_rta_del_partner_mas_de_7_dias = FormSubmission::where('form_submission_status_id', $delayedPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->whereDoesntHave('formResponses', function ($query) {
                $query->where('is_system', true); // Filtra respuestas enviadas por el partner
            })
            ->get();

        if ($submissions_sin_rta_del_partner_mas_de_7_dias->isNotEmpty()) {
            $this->makeUpdates($submissions_sin_rta_del_partner_mas_de_7_dias, $closedNoReplyPartnerId, 'Cerramos el ticket porque el partner no respondio ni una vez', 'explicacion de porque te se cierra el ticket por falta de respuesta del partner NUUUNCAAA CONTESTASTE');
        }

        // Buscar FormSubmissions "Respondido por el partner", sin respuestas por parte del usuario, excepto la primera que es la que origina el FormSubmission. Sin actividad en 7 días -> STATUS_CERRADO_SIN_RTA_USUARIO
        $now = Carbon::now();
        $submissions_sin_rta_del_usuario_mas_de_7_dias = FormSubmission::where('form_submission_status_id', $answeredPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->withCount([
                'formResponses as user_responses_count' => function ($query) {
                    $query->where('is_system', false); // Cuenta solo respuestas del usuario
                },
                'formResponses as partner_responses_count' => function ($query) {
                    $query->where('is_system', true); // Cuenta solo respuestas del partner
                }
            ])
            ->having('user_responses_count', '=', 1) // Usuario tiene solo 1 respuesta
            ->having('partner_responses_count', '>=', 1) // Partner tiene 1 o más respuestas
            ->get();

        if ($submissions_sin_rta_del_usuario_mas_de_7_dias->isNotEmpty()) {
            $this->makeUpdates($submissions_sin_rta_del_usuario_mas_de_7_dias, $closedNoReplyUserId, 'Cerramos el ticket porque el usuario nunca respondio', 'explicacion de porque te se cierra el ticket por falta de respuesta del usuario SABEMOS QUE CONTACTASTE AL TIPO PERO NO RESPONDIO MAS');
        }

        // Buscar FormSubmissions "Demorado de rta del Partner" sin actividad en 7 días -> STATUS_CERRADO_SIN_RTA_PARTNER
        $now = Carbon::now();
        $submissions_delayedPartnerStatus = FormSubmission::where('form_submission_status_id', $delayedPartnerId)
            ->where('updated_at', '<', $now->subDays(7))
            ->get();

        if ($submissions_delayedPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_delayedPartnerStatus, $closedNoReplyPartnerId, 'Cerramos el ticket por falta de actividad en rta del partner', 'explicacion de porque te se cierra el ticket por falta de respuesta del partner');
        }

        // Buscar FormSubmissions "Respondido Por El Partner" sin actividad en 7 días por parte del usuario -> STATUS_CERRADO_SIN_RTA_USUARIO
        $now = Carbon::now();
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

            if ($formSubmission->user->email) {
                $this->sendEmailWithChangesToPartner($formSubmission, $subject, $msg);
            }

            $this->updateFormSubmissionStatus($formSubmission, $statusId);
        }
    }

    public function updateFormSubmissionStatus($formSubmission, $statusId)
    {
        // Actualizar el estado del FormSubmission
        $formSubmission->update(['form_submission_status_id' => $statusId]);
    }

    public function sendEmailWithChangesToPartner($formSubmission, $subject, $msg)
    {
        // Enviar el correo usando un Job
        SendFormStatusChangeToPartner::dispatch($formSubmission, $subject, $msg)->delay(now()->addSeconds(10));
    }
}
