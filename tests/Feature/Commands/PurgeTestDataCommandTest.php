<?php

namespace Tests\Feature\Commands;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\TransactionalEmail;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurgeTestDataCommandTest extends TestCase
{
    use RefreshDatabase;

    private const CONFIRMATION = 'Delete all of the above? This cannot be undone.';

    protected User $admin;

    protected User $partner;

    protected User $trashedPartner;

    protected FormSubmissionStatus $status;

    protected TransactionalEmail $email;

    protected Province $province;

    protected Zone $zone;

    protected Locality $locality;

    protected Locality $trashedLocality;

    protected Locality $unassignedLocality;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create(['email' => 'admin@example.com']);
        $this->partner = User::factory()->partner()->create(['email' => 'partner@example.com']);
        $this->trashedPartner = User::factory()->partner()->create(['email' => 'trashed@example.com']);

        $this->status = FormSubmissionStatus::factory()->create();
        $this->email = TransactionalEmail::factory()->create();

        $this->province = Province::factory()->create();
        $this->zone = Zone::factory()->forProvince($this->province)->create();

        $this->locality = Locality::factory()->inZone($this->zone)->forPartner($this->partner)->create();
        $this->trashedLocality = Locality::factory()->inZone($this->zone)->forPartner($this->trashedPartner)->create();
        $this->unassignedLocality = Locality::factory()->inZone($this->zone)->withoutPartner()->create();

        $this->trashedLocality->delete();
        $this->trashedPartner->delete();
    }

    protected function seedTestData(): array
    {
        $submission = FormSubmission::factory()->forLocality($this->locality)->create([
            'form_submission_status_id' => $this->status->id,
        ]);
        $trashedSubmission = FormSubmission::factory()->forLocality($this->locality)->create([
            'form_submission_status_id' => $this->status->id,
        ]);

        $response = FormResponse::factory()->forSubmission($submission)->create();
        $trashedResponse = FormResponse::factory()->forSubmission($trashedSubmission)->create();

        DB::table('form_submission_notifications')->insert([
            'form_submission_id' => $submission->id,
            'new_status_id' => $this->status->id,
        ]);

        $trashedResponse->delete();
        $trashedSubmission->delete();

        DB::table('sessions')->insert([
            ['id' => 'partner-session', 'user_id' => $this->partner->id, 'payload' => '', 'last_activity' => 0],
            ['id' => 'trashed-session', 'user_id' => $this->trashedPartner->id, 'payload' => '', 'last_activity' => 0],
            ['id' => 'admin-session', 'user_id' => $this->admin->id, 'payload' => '', 'last_activity' => 0],
        ]);

        DB::table('password_reset_tokens')->insert([
            ['email' => $this->partner->email, 'token' => 'partner-token'],
            ['email' => $this->admin->email, 'token' => 'admin-token'],
        ]);

        DB::table('personal_access_tokens')->insert([
            ['tokenable_type' => User::class, 'tokenable_id' => $this->partner->id, 'name' => 'test', 'token' => str_repeat('a', 64)],
            ['tokenable_type' => User::class, 'tokenable_id' => $this->admin->id, 'name' => 'test', 'token' => str_repeat('b', 64)],
        ]);

        DB::table('jobs')->insert([
            'queue' => 'default',
            'payload' => '{}',
            'attempts' => 0,
            'available_at' => 0,
            'created_at' => 0,
        ]);

        DB::table('failed_jobs')->insert([
            'uuid' => 'failed-job',
            'connection' => 'database',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'Exception',
        ]);

        return compact('submission', 'trashedSubmission', 'response', 'trashedResponse');
    }

    /** @test */
    public function it_purges_partners_submissions_and_queue_leftovers_with_force()
    {
        $records = $this->seedTestData();

        $this->artisan('app:purge-test-data', ['--force' => true])->assertSuccessful();

        $this->assertDatabaseMissing('users', ['id' => $this->partner->id]);
        $this->assertDatabaseMissing('users', ['id' => $this->trashedPartner->id]);
        $this->assertSame(0, User::withTrashed()->where('role', User::PARTNER_USER)->count());

        $this->assertDatabaseMissing('form_submissions', ['id' => $records['submission']->id]);
        $this->assertDatabaseMissing('form_submissions', ['id' => $records['trashedSubmission']->id]);
        $this->assertDatabaseMissing('form_responses', ['id' => $records['response']->id]);
        $this->assertDatabaseMissing('form_responses', ['id' => $records['trashedResponse']->id]);
        $this->assertDatabaseCount('form_submission_notifications', 0);
        $this->assertDatabaseCount('jobs', 0);
        $this->assertDatabaseCount('failed_jobs', 0);

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
        $this->assertDatabaseHas('form_submission_statuses', ['id' => $this->status->id]);
        $this->assertDatabaseHas('transactional_emails', ['id' => $this->email->id]);
        $this->assertDatabaseHas('provinces', ['id' => $this->province->id]);
        $this->assertDatabaseHas('zones', ['id' => $this->zone->id]);

        // Localities survive the purge, detached from their former partners
        $this->assertDatabaseHas('localities', ['id' => $this->locality->id, 'user_id' => null]);
        $this->assertDatabaseHas('localities', ['id' => $this->trashedLocality->id, 'user_id' => null]);
        $this->assertDatabaseHas('localities', ['id' => $this->unassignedLocality->id, 'user_id' => null]);
        $this->assertTrue(Locality::withTrashed()->find($this->trashedLocality->id)->trashed());
    }

    /** @test */
    public function it_removes_partner_sessions_and_tokens_but_keeps_the_admin_ones()
    {
        $this->seedTestData();

        $this->artisan('app:purge-test-data', ['--force' => true])->assertSuccessful();

        $this->assertDatabaseMissing('sessions', ['id' => 'partner-session']);
        $this->assertDatabaseMissing('sessions', ['id' => 'trashed-session']);
        $this->assertDatabaseHas('sessions', ['id' => 'admin-session']);

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $this->partner->email]);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $this->admin->email]);

        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $this->partner->id]);
        $this->assertDatabaseHas('personal_access_tokens', ['tokenable_id' => $this->admin->id]);
    }

    /** @test */
    public function it_changes_nothing_when_the_confirmation_is_declined()
    {
        $records = $this->seedTestData();

        $this->artisan('app:purge-test-data')
            ->expectsConfirmation(self::CONFIRMATION, 'no')
            ->expectsOutputToContain('Aborted')
            ->assertFailed();

        $this->assertDatabaseHas('users', ['id' => $this->partner->id]);
        $this->assertNotNull(User::withTrashed()->find($this->trashedPartner->id));
        $this->assertNotNull(FormSubmission::withTrashed()->find($records['trashedSubmission']->id));
        $this->assertDatabaseHas('form_submissions', ['id' => $records['submission']->id]);
        $this->assertDatabaseHas('form_responses', ['id' => $records['response']->id]);
        $this->assertDatabaseCount('form_submission_notifications', 1);
        $this->assertDatabaseCount('sessions', 3);
        $this->assertDatabaseCount('jobs', 1);
        $this->assertDatabaseCount('failed_jobs', 1);
        $this->assertDatabaseHas('localities', ['id' => $this->locality->id, 'user_id' => $this->partner->id]);
    }

    /** @test */
    public function it_purges_after_the_confirmation_is_accepted()
    {
        $this->seedTestData();

        $this->artisan('app:purge-test-data')
            ->expectsConfirmation(self::CONFIRMATION, 'yes')
            ->assertSuccessful();

        $this->assertSame(0, User::withTrashed()->where('role', User::PARTNER_USER)->count());
        $this->assertDatabaseCount('form_submissions', 0);
        $this->assertDatabaseHas('localities', ['id' => $this->locality->id, 'user_id' => null]);
    }

    /** @test */
    public function it_succeeds_on_an_already_clean_database()
    {
        $this->artisan('app:purge-test-data', ['--force' => true])->assertSuccessful();

        $this->artisan('app:purge-test-data')
            ->expectsOutputToContain('Nothing to purge')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
        $this->assertDatabaseCount('localities', 3);
    }
}
