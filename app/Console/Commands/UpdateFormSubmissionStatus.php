<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FormSubmission;
use Illuminate\Console\Command;
use App\Jobs\SendFormStatusChange;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Schedule;
use App\Services\TransactionalEmailService;

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
        //TODO: Refactorizare este metodo

        $emailService = new TransactionalEmailService('cambio de estado');

        // Mail para cuando el estado es "Demorado - Sin Respuesta Del Partner (48h)"
        $delayedNoReplyFromPartnerEmailToPartner = $emailService->getEmail(FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER, 'partner');

        // Mails para cuando el estado es "Cerrado - Sin Respuesta Del Partner"
        $closedNoReplyFromPartnerEmailToPartner = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER, 'partner', 'respondio_antes');
        $closedPartnerNeverReplyEmailToPartner = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER, 'partner', 'nunca_respondio');
        $closedNoReplyFromPartnerEmailToUser = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER, 'user');

        // Mails para cuando el estado es "Cerrado - Sin Respuesta Del Usuario"
        $closedNoReplyFromUserEmailToPartner = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO, 'partner', 'respondio_antes');
        $closedUserNeverReplyEmailToPartner = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO, 'partner', 'nunca_respondio');
        $closedNoReplyFromUserEmailToUser = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO, 'user', 'respondio_antes');
        $closedUserNeverReplyEmailToUser = $emailService->getEmail(FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO, 'user', 'nunca_respondio');

        // Obtener los estados necesarios desde la base de datos
        $pendingPartnerStatus = FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->first();
        $answeredPartnerStatus = FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)->first();
        $delayedPartnerStatus = FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER)->first();
        $closedNoReplyPartnerStatus = FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER)->first();
        $closedNoReplyUserStatus = FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO)->first();

        // Buscar FormSubmissions con estado "Pendiente de Respuesta Del Partner" y sin actividad en 48h -> STATUS_DEMORADO_POR_PARTNER
        $now = Carbon::now();
        $submissions_pendingPartnerStatus = FormSubmission::where('form_submission_status_id', $pendingPartnerStatus->id)
            ->where('updated_at', '<', $now->subHours(48))
            ->get();

        if ($submissions_pendingPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_pendingPartnerStatus, $delayedPartnerStatus->id, $delayedNoReplyFromPartnerEmailToPartner);
        }


        // Buscar FormSubmissions "Demorado de rta del Partner" sin actividad en 7 días y que no tenga ni una respuesta del partner -> STATUS_CERRADO_SIN_RTA_PARTNER
        $now = Carbon::now();
        $submissions_sin_rta_del_partner_mas_de_7_dias = FormSubmission::where('form_submission_status_id', $delayedPartnerStatus->id)
            ->where('updated_at', '<', $now->subDays(7))
            ->whereDoesntHave('formResponses', function ($query) {
                $query->where('is_system', true); // Filtra respuestas enviadas por el partner
            })
            ->get();

        if ($submissions_sin_rta_del_partner_mas_de_7_dias->isNotEmpty()) {
            $this->makeUpdates($submissions_sin_rta_del_partner_mas_de_7_dias, $closedNoReplyPartnerStatus->id, $closedPartnerNeverReplyEmailToPartner, $closedNoReplyFromPartnerEmailToUser, config('form_submission_closure_reasons.closure_reasons.closed_no_reply_never_partner'));
        }

        // Buscar FormSubmissions "Respondido por el partner", sin respuestas por parte del usuario, excepto la primera que es la que origina el FormSubmission. Sin actividad en 7 días -> STATUS_CERRADO_SIN_RTA_USUARIO
        $now = Carbon::now();
        $submissions_sin_rta_del_usuario_mas_de_7_dias = FormSubmission::where('form_submission_status_id', $answeredPartnerStatus->id)
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
            $this->makeUpdates($submissions_sin_rta_del_usuario_mas_de_7_dias, $closedNoReplyUserStatus->id, $closedUserNeverReplyEmailToPartner, $closedUserNeverReplyEmailToUser, config('form_submission_closure_reasons.closure_reasons.closed_no_reply_user'));
        }

        // Buscar FormSubmissions "Demorado de rta del Partner" sin actividad en 7 días -> STATUS_CERRADO_SIN_RTA_PARTNER
        $now = Carbon::now();
        $submissions_delayedPartnerStatus = FormSubmission::where('form_submission_status_id', $delayedPartnerStatus->id)
            ->where('updated_at', '<', $now->subDays(7))
            ->get();

        if ($submissions_delayedPartnerStatus->isNotEmpty()) {
            $this->makeUpdates($submissions_delayedPartnerStatus, $closedNoReplyPartnerStatus->id, $closedNoReplyFromPartnerEmailToPartner, $closedNoReplyFromPartnerEmailToUser, config('form_submission_closure_reasons.closure_reasons.closed_no_reply_partner'));
        }

        // Buscar FormSubmissions "Respondido Por El Partner" sin actividad en 7 días por parte del usuario -> STATUS_CERRADO_SIN_RTA_USUARIO
        $now = Carbon::now();
        $submissions_answeredByPartner = FormSubmission::where('form_submission_status_id', $answeredPartnerStatus->id)
            ->where('updated_at', '<', $now->subDays(7))
            ->get();

        if ($submissions_answeredByPartner->isNotEmpty()) {
            $this->makeUpdates($submissions_answeredByPartner, $closedNoReplyUserStatus->id, $closedNoReplyFromUserEmailToPartner, $closedNoReplyFromUserEmailToUser, config('form_submission_closure_reasons.closure_reasons.closed_no_reply_user'));
        }

        $this->info('Estados de FormSubmissions actualizados correctamente.');
    }

    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->hourly();
    }

    public function makeUpdates($array, $statusId, $emailTemplateToPartner, $emailTemplateToUser = NULL, $closure_reason = NULL)
    {

        foreach ($array as $formSubmission) {

            if ($formSubmission->user->email) {
                $this->sendEmailWithChanges($formSubmission, $formSubmission->user->email, $emailTemplateToPartner);
            }

            if ($emailTemplateToUser) {
                $user = json_decode($formSubmission->data, true);
                $this->sendEmailWithChanges($formSubmission, $user['email'],  $emailTemplateToUser);
            }

            $this->updateFormSubmissionStatus($formSubmission, $statusId, $closure_reason);
        }
    }

    public function updateFormSubmissionStatus($formSubmission, $statusId, $closure_reason)
    {
        // Actualizar el estado del FormSubmission
        $formSubmission->update(['form_submission_status_id' => $statusId]);

        // Actualizar el closure reason
        $formSubmission->update(['closure_reason' => $closure_reason]);
    }

    public function sendEmailWithChanges($formSubmission, $recipent, $emailTemplate)
    {
        // Enviar el correo usando un Job
        SendFormStatusChange::dispatch($formSubmission, $recipent, $emailTemplate)->delay(now()->addSeconds(10));
    }
}
