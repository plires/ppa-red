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
     * Servicio de emails transaccionales
     */
    protected TransactionalEmailService $emailService;

    /**
     * Colección de estados de FormSubmission
     */
    protected array $statuses = [];

    /**
     * Colección de plantillas de correo
     */
    protected array $emailTemplates = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->emailService = new TransactionalEmailService('cambio de estado');

        $this->loadStatuses();
        $this->loadEmailTemplates();

        $this->processPendingSubmissions();
        $this->processDelayedSubmissionsWithNoPartnerResponse();
        $this->processAnsweredSubmissionsWithNoUserResponse();
        $this->processAllDelayedSubmissions();
        $this->processAllAnsweredSubmissions();

        $this->info('Estados de FormSubmissions actualizados correctamente.');
    }

    /**
     * Carga los estados necesarios desde la base de datos
     */
    protected function loadStatuses(): void
    {
        $this->statuses = [
            'pendingPartner' => FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->first(),
            'answeredPartner' => FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)->first(),
            'delayedPartner' => FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER)->first(),
            'closedNoReplyPartner' => FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER)->first(),
            'closedNoReplyUser' => FormSubmissionStatus::where('name', FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO)->first(),
        ];
    }

    /**
     * Carga las plantillas de correo electrónico
     */
    protected function loadEmailTemplates(): void
    {
        $this->emailTemplates = [
            'delayedNoReplyFromPartnerToPartner' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                'partner'
            ),

            'closedNoReplyFromPartnerToPartner' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'partner',
                'respondio_antes'
            ),

            'closedPartnerNeverReplyToPartner' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'partner',
                'nunca_respondio'
            ),

            'closedNoReplyFromPartnerToUser' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'user'
            ),

            'closedNoReplyFromUserToPartner' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'partner',
                'respondio_antes'
            ),

            'closedUserNeverReplyToPartner' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'partner',
                'nunca_respondio'
            ),

            'closedNoReplyFromUserToUser' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'user',
                'respondio_antes'
            ),

            'closedUserNeverReplyToUser' => $this->emailService->getEmail(
                FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'user',
                'nunca_respondio'
            ),
        ];
    }

    /**
     * Procesa las submissions pendientes de respuesta del partner sin actividad en 48h
     */
    protected function processPendingSubmissions(): void
    {
        $now = Carbon::now();
        $submissions = FormSubmission::where('form_submission_status_id', $this->statuses['pendingPartner']->id)
            ->where('updated_at', '<', $now->copy()->subHours(48))
            ->get();

        if ($submissions->isNotEmpty()) {
            $this->makeUpdates(
                $submissions,
                $this->statuses['delayedPartner']->id,
                $this->emailTemplates['delayedNoReplyFromPartnerToPartner']
            );
        }
    }

    /**
     * Procesa las submissions demoradas sin ninguna respuesta del partner en 7 días
     */
    protected function processDelayedSubmissionsWithNoPartnerResponse(): void
    {
        $now = Carbon::now();
        $submissions = FormSubmission::where('form_submission_status_id', $this->statuses['delayedPartner']->id)
            ->where('updated_at', '<', $now->copy()->subDays(7))
            ->whereDoesntHave('formResponses', function ($query) {
                $query->where('is_system', true); // Filtra respuestas enviadas por el partner
            })
            ->get();

        if ($submissions->isNotEmpty()) {
            $this->makeUpdates(
                $submissions,
                $this->statuses['closedNoReplyPartner']->id,
                $this->emailTemplates['closedPartnerNeverReplyToPartner'],
                $this->emailTemplates['closedNoReplyFromPartnerToUser'],
                config('form_submission_closure_reasons.closure_reasons.closed_no_reply_never_partner')
            );
        }
    }

    /**
     * Procesa las submissions respondidas por el partner sin respuesta del usuario en 7 días
     */
    protected function processAnsweredSubmissionsWithNoUserResponse(): void
    {
        $now = Carbon::now();
        $submissions = FormSubmission::where('form_submission_status_id', $this->statuses['answeredPartner']->id)
            ->where('updated_at', '<', $now->copy()->subDays(7))
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

        if ($submissions->isNotEmpty()) {
            $this->makeUpdates(
                $submissions,
                $this->statuses['closedNoReplyUser']->id,
                $this->emailTemplates['closedUserNeverReplyToPartner'],
                $this->emailTemplates['closedUserNeverReplyToUser'],
                config('form_submission_closure_reasons.closure_reasons.closed_no_reply_user')
            );
        }
    }

    /**
     * Procesa todas las submissions demoradas sin actividad en 7 días
     */
    protected function processAllDelayedSubmissions(): void
    {
        $now = Carbon::now();
        $submissions = FormSubmission::where('form_submission_status_id', $this->statuses['delayedPartner']->id)
            ->where('updated_at', '<', $now->copy()->subDays(7))
            ->get();

        if ($submissions->isNotEmpty()) {
            $this->makeUpdates(
                $submissions,
                $this->statuses['closedNoReplyPartner']->id,
                $this->emailTemplates['closedNoReplyFromPartnerToPartner'],
                $this->emailTemplates['closedNoReplyFromPartnerToUser'],
                config('form_submission_closure_reasons.closure_reasons.closed_no_reply_partner')
            );
        }
    }

    /**
     * Procesa todas las submissions respondidas por el partner sin actividad en 7 días
     */
    protected function processAllAnsweredSubmissions(): void
    {
        $now = Carbon::now();
        $submissions = FormSubmission::where('form_submission_status_id', $this->statuses['answeredPartner']->id)
            ->where('updated_at', '<', $now->copy()->subDays(7))
            ->get();

        if ($submissions->isNotEmpty()) {
            $this->makeUpdates(
                $submissions,
                $this->statuses['closedNoReplyUser']->id,
                $this->emailTemplates['closedNoReplyFromUserToPartner'],
                $this->emailTemplates['closedNoReplyFromUserToUser'],
                config('form_submission_closure_reasons.closure_reasons.closed_no_reply_user')
            );
        }
    }

    /**
     * Programa la ejecución del comando
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->hourly();
    }

    /**
     * Realiza las actualizaciones de estado y envío de emails
     */
    protected function makeUpdates($submissions, $statusId, $emailTemplateToPartner, $emailTemplateToUser = null, $closure_reason = null): void
    {
        foreach ($submissions as $formSubmission) {
            // Enviar email al partner
            if ($formSubmission->user && $formSubmission->user->email) {
                $this->sendEmailWithChanges($formSubmission, $formSubmission->user->email, $emailTemplateToPartner);
            }

            // Enviar email al usuario si existe plantilla
            if ($emailTemplateToUser) {
                $userData = json_decode($formSubmission->data, true);
                $this->sendEmailWithChanges($formSubmission, $userData['email'], $emailTemplateToUser);
            }

            // Actualizar el estado y razón de cierre
            $this->updateFormSubmissionStatus($formSubmission, $statusId, $closure_reason);
        }
    }

    /**
     * Actualiza el estado y razón de cierre de una submission
     */
    protected function updateFormSubmissionStatus($formSubmission, $statusId, $closure_reason): void
    {
        $formSubmission->update([
            'form_submission_status_id' => $statusId,
            'closure_reason' => $closure_reason
        ]);
    }

    /**
     * Envía un email con los cambios realizados
     */
    protected function sendEmailWithChanges($formSubmission, $recipient, $emailTemplate): void
    {
        SendFormStatusChange::dispatch($formSubmission, $recipient, $emailTemplate)
            ->delay(now()->addSeconds(10));
    }
}
