<?php

namespace Tests\Feature\Commands;

use App\Jobs\SendFormStatusChange;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateFormSubmissionStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $statuses = [];

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->setupStatuses();

        $this->user = User::factory()->create([
            'email' => 'partner@example.com',
        ]);

        // Configurar closure reasons en config
        config([
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_partner' => 'Cerrado por falta de respuesta del partner',
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_never_partner' => 'Cerrado porque el partner nunca respondió',
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_user' => 'Cerrado por falta de respuesta del usuario',
        ]);
    }

    protected function setupStatuses(): void
    {
        $this->statuses = [
            'pendingPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                'description' => 'Pendiente de respuesta del partner',
            ]),
            'answeredPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                'description' => 'Respondido por el partner',
            ]),
            'delayedPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                'description' => 'Demorado sin respuesta del partner',
            ]),
            'closedNoReplyPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'description' => 'Cerrado sin respuesta del partner',
            ]),
            'closedNoReplyUser' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'description' => 'Cerrado sin respuesta del usuario',
            ]),
        ];
    }

    /** @test */
    public function it_updates_pending_submissions_older_than_48_hours()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['pendingPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subHours(49),
        ]);

        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $this->assertEquals(
            $this->statuses['delayedPartner']->id,
            $submission->fresh()->form_submission_status_id
        );

        // El comando despacha al menos 1 job (hacia el partner)
        Queue::assertPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_does_not_update_pending_submissions_newer_than_48_hours()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['pendingPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subHours(47),
        ]);

        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $this->assertEquals(
            $this->statuses['pendingPartner']->id,
            $submission->fresh()->form_submission_status_id
        );

        Queue::assertNotPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_closes_delayed_submissions_with_no_partner_response_after_7_days()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['delayedPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8),
        ]);

        // Sin respuestas del partner — cae en processDelayedSubmissionsWithNoPartnerResponse
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyPartner']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado porque el partner nunca respondió',
            $submission->closure_reason
        );

        Queue::assertPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_closes_answered_submissions_with_no_user_response_after_7_days()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['answeredPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8),
        ]);

        // Simular 1 respuesta de usuario y 1 del partner usando registros reales
        FormResponse::create([
            'form_submission_id' => $submission->id,
            'user_id' => $this->user->id,
            'message' => 'Mensaje del usuario',
            'is_system' => false,
        ]);
        FormResponse::create([
            'form_submission_id' => $submission->id,
            'user_id' => $this->user->id,
            'message' => 'Respuesta del partner',
            'is_system' => true,
        ]);

        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyUser']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del usuario',
            $submission->closure_reason
        );

        Queue::assertPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_closes_all_delayed_submissions_after_7_days()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['delayedPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8),
        ]);

        // Agregar una respuesta del partner para que NO caiga en processDelayedWithNoPartnerResponse
        // y sí caiga en processAllDelayedSubmissions
        FormResponse::create([
            'form_submission_id' => $submission->id,
            'user_id' => $this->user->id,
            'message' => 'Respuesta previa del partner',
            'is_system' => true,
        ]);

        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyPartner']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del partner',
            $submission->closure_reason
        );

        Queue::assertPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_closes_all_answered_submissions_after_7_days()
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['answeredPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8),
        ]);

        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyUser']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del usuario',
            $submission->closure_reason
        );

        Queue::assertPushed(SendFormStatusChange::class);
    }
}
