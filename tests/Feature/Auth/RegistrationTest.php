<?php

use App\Models\User;

test('legacy registration route is gone even for admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/register');

    $response->assertNotFound();
});

test('legacy registration route is gone for guests', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});
