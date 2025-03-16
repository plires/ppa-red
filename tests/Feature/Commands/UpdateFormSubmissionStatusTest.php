<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Carbon\Carbon;
use App\Models\User;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Services\TransactionalEmailService;
use App\Jobs\SendFormStatusChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class UpdateFormSubmissionStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $statuses = [];
    protected $mockEmails = [];
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Interceptar envío de emails para testing
        Queue::fake();
        
        // Configurar estados de prueba
        $this->setupStatuses();
        
        // Configurar mocks de emails para testing
        $this->setupMockEmails();
        
        // Crear un usuario para testing
        $this->user = User::factory()->create([
            'email' => 'partner@example.com'
        ]);
    }

    protected function setupStatuses(): void
    {
        // Crear los estados necesarios para pruebas
        $this->statuses = [
            'pendingPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                'description' => 'Pendiente de respuesta del partner'
            ]),
            'answeredPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                'description' => 'Respondido por el partner'
            ]),
            'delayedPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                'description' => 'Demorado sin respuesta del partner'
            ]),
            'closedNoReplyPartner' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'description' => 'Cerrado sin respuesta del partner'
            ]),
            'closedNoReplyUser' => FormSubmissionStatus::create([
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'description' => 'Cerrado sin respuesta del usuario'
            ])
        ];
    }

    protected function setupMockEmails(): void
    {
        // Configurar mock del servicio de emails
        $this->mockEmails = [
            'delayedNoReplyFromPartnerToPartner' => (object)['id' => 1, 'subject' => 'Recordatorio de respuesta pendiente'],
            'closedNoReplyFromPartnerToPartner' => (object)['id' => 2, 'subject' => 'Consulta cerrada - Sin respuesta'],
            'closedPartnerNeverReplyToPartner' => (object)['id' => 3, 'subject' => 'Consulta cerrada - Nunca respondiste'],
            'closedNoReplyFromPartnerToUser' => (object)['id' => 4, 'subject' => 'Tu consulta ha sido cerrada'],
            'closedNoReplyFromUserToPartner' => (object)['id' => 5, 'subject' => 'Consulta cerrada por inactividad del usuario'],
            'closedUserNeverReplyToPartner' => (object)['id' => 6, 'subject' => 'Consulta cerrada - Usuario nunca respondió'],
            'closedNoReplyFromUserToUser' => (object)['id' => 7, 'subject' => 'Tu consulta ha sido cerrada por inactividad'],
            'closedUserNeverReplyToUser' => (object)['id' => 8, 'subject' => 'Tu consulta ha sido cerrada por falta de respuesta']
        ];

        // Mock del servicio TransactionalEmailService
        $this->mock(TransactionalEmailService::class, function ($mock) {
            foreach ($this->mockEmails as $key => $value) {
                $mock->shouldReceive('getEmail')->withAnyArgs()->andReturn($value);
            }
        });

        // Configurar closure reasons en config
        config([
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_partner' => 'Cerrado por falta de respuesta del partner',
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_never_partner' => 'Cerrado porque el partner nunca respondió',
            'form_submission_closure_reasons.closure_reasons.closed_no_reply_user' => 'Cerrado por falta de respuesta del usuario'
        ]);
    }

    /** @test */
    public function it_updates_pending_submissions_older_than_48_hours()
    {
        // Crear una submission pendiente con más de 48 horas
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['pendingPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subHours(49)
        ]);

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission cambió a estado demorado
        $this->assertEquals(
            $this->statuses['delayedPartner']->id,
            $submission->fresh()->form_submission_status_id
        );

        // Verificar que se programó el envío del correo
        Queue::assertPushed(SendFormStatusChange::class, function ($job) use ($submission) {
            return $job->formSubmission->id === $submission->id && 
                   $job->recipient === 'partner@example.com';
        });
    }

    /** @test */
    public function it_does_not_update_pending_submissions_newer_than_48_hours()
    {
        // Crear una submission pendiente con menos de 48 horas
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['pendingPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subHours(47)
        ]);

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission NO cambió de estado
        $this->assertEquals(
            $this->statuses['pendingPartner']->id,
            $submission->fresh()->form_submission_status_id
        );

        // Verificar que NO se programó envío de correo
        Queue::assertNotPushed(SendFormStatusChange::class);
    }

    /** @test */
    public function it_closes_delayed_submissions_with_no_partner_response_after_7_days()
    {
        // Crear una submission demorada con más de 7 días
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['delayedPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8)
        ]);

        // No crear respuestas del partner para que entre en el criterio

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission cambió a estado cerrado sin respuesta del partner
        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyPartner']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado porque el partner nunca respondió', 
            $submission->closure_reason
        );

        // Verificar que se programaron los envíos de correo al partner y al usuario
        Queue::assertPushed(SendFormStatusChange::class, 2);
    }

    /** @test */
    public function it_closes_answered_submissions_with_no_user_response_after_7_days()
    {
        // Mockear la relación formResponses para simular conteos
        $submission = $this->createMockSubmissionWithResponseCounts(
            $this->statuses['answeredPartner']->id,
            1, // user_responses_count 
            1  // partner_responses_count
        );

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission cambió a estado cerrado sin respuesta del usuario
        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyUser']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del usuario', 
            $submission->closure_reason
        );

        // Verificar que se programaron los envíos de correo
        Queue::assertPushed(SendFormStatusChange::class, 2);
    }

    /** @test */
    public function it_closes_all_delayed_submissions_after_7_days()
    {
        // Crear una submission demorada con más de 7 días (con respuestas del partner)
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['delayedPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8)
        ]);

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission cambió a estado cerrado sin respuesta del partner
        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyPartner']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del partner', 
            $submission->closure_reason
        );

        // Verificar que se programaron los envíos de correo
        Queue::assertPushed(SendFormStatusChange::class, 2);
    }

    /** @test */
    public function it_closes_all_answered_submissions_after_7_days()
    {
        // Crear una submission respondida con más de 7 días
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $this->statuses['answeredPartner']->id,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8)
        ]);

        // Ejecutar el comando
        $this->artisan('formsubmissions:update-status')->assertSuccessful();

        // Verificar que la submission cambió a estado cerrado sin respuesta del usuario
        $submission = $submission->fresh();
        $this->assertEquals($this->statuses['closedNoReplyUser']->id, $submission->form_submission_status_id);
        $this->assertEquals(
            'Cerrado por falta de respuesta del usuario', 
            $submission->closure_reason
        );

        // Verificar que se programaron los envíos de correo
        Queue::assertPushed(SendFormStatusChange::class, 2);
    }

    // Método auxiliar para crear submissions con conteos específicos de respuestas
    protected function createMockSubmissionWithResponseCounts($statusId, $userCount, $partnerCount)
    {
        $submission = FormSubmission::factory()->create([
            'user_id' => $this->user->id,
            'form_submission_status_id' => $statusId,
            'data' => json_encode(['email' => 'user@example.com']),
            'updated_at' => now()->subDays(8)
        ]);

        // Mockear la relación withCount
        $mock = $this->partialMock(FormSubmission::class, function ($mock) use ($userCount, $partnerCount) {
            $mock->shouldReceive('withCount')->andReturnSelf();
            $mock->shouldReceive('having')->withAnyArgs()->andReturnSelf();
            $mock->shouldReceive('get')->andReturn(collect([$mock]));
            $mock->user_responses_count = $userCount;
            $mock->partner_responses_count = $partnerCount;
            $mock->user = $this->user;
            $mock->data = json_encode(['email' => 'user@example.com']);
        });

        return $submission;
    }
}
