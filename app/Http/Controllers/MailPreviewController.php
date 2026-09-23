<?php

namespace App\Http\Controllers;

use App\Mail\FormResponseMailToPartner;
use App\Mail\FormResponseMailToUser;
use App\Mail\FormResponseUnassignedMail;
use App\Mail\FormSubmissionConfirmationMail;
use App\Mail\FormSubmissionUnassignedMail;
use App\Mail\MailFormSubmissionStatusChange;
use App\Mail\PartnerWelcomeMail;
use App\Mail\ReassignIncomingPartnerMail;
use App\Mail\ReassignOutgoingPartnerMail;
use App\Mail\ReassignUserMail;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailable;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Preview de las plantillas de email, sólo en local (ver routes/web.php).
 *
 * Renderiza cada Mailable con datos de prueba armados en memoria (nunca se
 * persiste nada), así que el preview no depende de que la base esté sembrada
 * ni ensucia los datos reales con envíos de prueba.
 */
class MailPreviewController extends Controller
{
    private const TEMPLATES = [
        'form_submission_confirmation' => 'Confirmación al solicitante',
        'form_submission_unassigned' => 'Consulta sin partner — aviso al admin',
        'form_response_to_partner' => 'Nueva consulta — aviso al partner',
        'form_response_to_user' => 'Respuesta del partner — aviso al usuario',
        'form_response_unassigned' => 'Mensaje sin partner — aviso al admin',
        'status_change_partner' => 'Cambio de estado — al partner',
        'status_change_user' => 'Cambio de estado — al usuario',
        'partner_welcome' => 'Bienvenida al partner',
        'reassign_incoming_partner' => 'Reasignación — partner entrante',
        'reassign_outgoing_partner' => 'Reasignación — partner saliente',
        'reassign_user' => 'Reasignación — usuario',
    ];

    public function index(): InertiaResponse
    {
        abort_unless(app()->environment('local'), 404);

        return Inertia::render('Dev/MailPreview', [
            'templates' => collect(self::TEMPLATES)
                ->map(fn ($label, $key) => ['key' => $key, 'label' => $label])
                ->values(),
        ]);
    }

    public function render(string $template): Response
    {
        abort_unless(app()->environment('local'), 404);
        abort_unless(array_key_exists($template, self::TEMPLATES), 404);

        return response($this->buildMailable($template)->render());
    }

    private function buildMailable(string $template): Mailable
    {
        return match ($template) {
            'form_submission_confirmation' => new FormSubmissionConfirmationMail(
                $this->fakeSubmission($this->fakePartner()),
                $this->fakeRequesterData(),
            ),
            'form_submission_unassigned' => new FormSubmissionUnassignedMail(
                $this->fakeSubmission(),
                $this->fakeRequesterData(),
            ),
            'form_response_to_partner' => $this->fakeFormResponseToPartnerMail(),
            'form_response_to_user' => $this->fakeFormResponseToUserMail(),
            'form_response_unassigned' => $this->fakeFormResponseUnassignedMail(),
            'status_change_partner' => $this->fakeStatusChangeMail('partner'),
            'status_change_user' => $this->fakeStatusChangeMail('user'),
            'partner_welcome' => new PartnerWelcomeMail(
                $this->fakePartner(),
                url('/set-password/demo-token'),
            ),
            'reassign_incoming_partner' => new ReassignIncomingPartnerMail(
                $this->fakeSubmission(),
                $this->fakePartner(['name' => 'Roberto Instalaciones']),
                $this->fakeRequesterData(),
            ),
            'reassign_outgoing_partner' => new ReassignOutgoingPartnerMail(
                $this->fakeSubmission(),
                $this->fakePartner(['id' => 502, 'name' => 'Marcela Servicios', 'email' => 'marcela@ejemplo.com']),
                $this->fakePartner(['name' => 'Roberto Instalaciones']),
                $this->fakeRequesterData(),
            ),
            'reassign_user' => new ReassignUserMail(
                $this->fakeSubmission(),
                $this->fakePartner(['name' => 'Roberto Instalaciones']),
                'Juana Solicitante',
            ),
        };
    }

    private function fakeFormResponseToPartnerMail(): FormResponseMailToPartner
    {
        $submission = $this->fakeSubmission($this->fakePartner());
        $data = $this->fakeRequesterData();

        return new FormResponseMailToPartner(
            $this->fakeFormResponse($submission, null, $data['message']),
            $submission,
            $data,
        );
    }

    private function fakeFormResponseToUserMail(): FormResponseMailToUser
    {
        $partner = $this->fakePartner();
        $submission = $this->fakeSubmission($partner);
        $response = $this->fakeFormResponse(
            $submission,
            $partner,
            'Ya revisamos tu instalación. En las próximas 48hs coordinamos una visita técnica.'
        );

        return new FormResponseMailToUser($response, $this->fakeRequesterData());
    }

    private function fakeFormResponseUnassignedMail(): FormResponseUnassignedMail
    {
        $submission = $this->fakeSubmission();
        $response = $this->fakeFormResponse($submission, null, 'Hola, ¿tienen novedades sobre mi consulta?');

        return new FormResponseUnassignedMail($response, $submission, $this->fakeRequesterData());
    }

    private function fakeStatusChangeMail(string $recipientType): MailFormSubmissionStatusChange
    {
        $partner = $this->fakePartner();
        $submission = $this->fakeSubmission($partner, FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER);

        $submission->setRelation('formResponses', collect([
            $this->fakeFormResponse($submission, null, 'Hola, necesito instalar un sistema de riego. ¿Podrían contactarme?', now()->subDays(2)),
            $this->fakeFormResponse($submission, $partner, 'Ya coordinamos la visita técnica para mañana a las 10hs.', now()->subDay()),
        ]));

        $emailTemplate = (object) [
            'subject' => 'Tu consulta pasó a estado Demorado',
            'body' => 'Todavía no pudimos coordinar con vos. Por favor respondé a este mensaje o comunicate con tu instalador asignado para continuar el trámite.',
        ];

        return new MailFormSubmissionStatusChange($submission, $emailTemplate, $recipientType);
    }

    private function fakePartner(array $overrides = []): User
    {
        return (new User)->forceFill(array_merge([
            'id' => 501,
            'name' => 'Roberto Instalaciones',
            'email' => 'roberto@ejemplo.com',
            'phone' => '+54 9 11 5555-1234',
            'role' => User::PARTNER_USER,
        ], $overrides));
    }

    private function fakeRequesterData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Juana Solicitante',
            'email' => 'juana@ejemplo.com',
            'phone' => '+54 9 11 4444-9876',
            'message' => 'Hola, necesito instalar un sistema de riego en mi campo. ¿Podrían contactarme para coordinar una visita?',
        ], $overrides);
    }

    private function fakeSubmission(?User $partner = null, ?string $statusName = null): FormSubmission
    {
        $province = (new Province)->forceFill(['id' => 1, 'name' => 'Buenos Aires']);
        $zone = (new Zone)->forceFill(['id' => 1, 'name' => 'Zona Norte', 'province_id' => 1]);
        $locality = (new Locality)->forceFill(['id' => 1, 'name' => 'San Isidro', 'province_id' => 1, 'zone_id' => 1]);
        $locality->setRelation('province', $province);

        $status = (new FormSubmissionStatus)->forceFill([
            'id' => 1,
            'name' => $statusName ?? FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
        ]);

        $submission = (new FormSubmission)->forceFill([
            'id' => 999,
            'user_id' => $partner?->id,
            'province_id' => $province->id,
            'zone_id' => $zone->id,
            'locality_id' => $locality->id,
            'data' => json_encode($this->fakeRequesterData()),
            'form_submission_status_id' => $status->id,
            'secure_token' => 'demo-secure-token',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subHours(6),
        ]);

        $submission->setRelation('province', $province);
        $submission->setRelation('zone', $zone);
        $submission->setRelation('locality', $locality);
        $submission->setRelation('user', $partner);
        $submission->setRelation('status', $status);
        $submission->setRelation('formResponses', collect());

        return $submission;
    }

    private function fakeFormResponse(FormSubmission $submission, ?User $author, string $message, ?Carbon $createdAt = null): FormResponse
    {
        $response = (new FormResponse)->forceFill([
            'id' => random_int(1000, 9999),
            'form_submission_id' => $submission->id,
            'user_id' => $author?->id,
            'message' => $message,
            'is_system' => false,
            'created_at' => $createdAt ?? now(),
        ]);

        $response->setRelation('formSubmission', $submission);
        $response->setRelation('user', $author);

        return $response;
    }
}
