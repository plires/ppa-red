<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->patch('/dashboard/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->patch('/dashboard/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_users_cannot_delete_their_own_account(): void
    {
        foreach (['admin', 'partner'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this
                ->actingAs($user)
                ->delete('/dashboard/profile', [
                    'password' => 'password',
                ])
                ->assertMethodNotAllowed();

            $this->assertAuthenticatedAs($user);
            $this->assertNotSoftDeleted('users', ['id' => $user->id]);
        }
    }
}
