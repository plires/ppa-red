<?php

use App\Models\User;

test('registration screen can be rendered by admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/register');

    $response->assertStatus(200);
});

test('new users can be registered by admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '1234567890',
    ]);

    $response->assertRedirect(route('partners.index', absolute: false));
});

test('guests cannot access registration screen', function () {
    $response = $this->get('/register');

    $response->assertRedirect('/login');
});
