<?php

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Recepción de diagnósticos del cliente
|--------------------------------------------------------------------------
|
| Endpoint interno para capturar la respuesta que rompe la navegación de
| Inertia en producción. El crash ocurre al azar y sólo en producción, así que
| sin registrarlo del lado del servidor no hay forma de verlo: el usuario sólo
| percibe una recarga.
|
| Es un endpoint que escribe en los logs a partir de datos del navegador, así
| que la validación y los topes de tamaño son parte del contrato, no un extra.
|
*/

function diagnosticPayload(array $overrides = []): array
{
    return array_merge([
        'kind' => 'inertia_malformed_page',
        'page_url' => 'https://plires.cloud/dashboard/form_submissions',
        'request_url' => 'https://plires.cloud/dashboard/form_submissions',
        'status' => 200,
        'content_type' => 'application/json',
        'content_length_header' => 82031,
        'received_length' => 41015,
        'body_head' => '{"component":"FormSubmissions/Index","props":{"formSubmissions":[{"id":1,',
        'body_tail' => '"locality":{"id":7,"name":"Villa Carlos',
    ], $overrides);
}

it('requires authentication', function () {
    $this->postJson(route('client_diagnostics.store'), diagnosticPayload())
        ->assertUnauthorized();
});

it('records the failing response in the log', function () {
    Log::spy();

    $user = partner();

    $this->actingAs($user)
        ->postJson(route('client_diagnostics.store'), diagnosticPayload())
        ->assertNoContent();

    Log::shouldHaveReceived('warning')
        ->once()
        ->withArgs(function (string $message, array $context) use ($user) {
            return str_contains($message, 'inertia_malformed_page')
                && $context['user_id'] === $user->id
                && $context['status'] === 200
                && $context['content_length_header'] === 82031
                && $context['received_length'] === 41015
                && str_contains($context['body_head'], 'FormSubmissions/Index');
        });
});

it('flags a response whose body arrived short', function () {
    Log::spy();

    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([
            'content_length_header' => 82031,
            'received_length' => 41015,
        ]))->assertNoContent();

    Log::shouldHaveReceived('warning')
        ->withArgs(fn (string $message, array $context) => $context['looks_truncated'] === true);
});

it('does not flag truncation when the body arrived whole', function () {
    Log::spy();

    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([
            'content_length_header' => 82031,
            'received_length' => 82031,
        ]))->assertNoContent();

    Log::shouldHaveReceived('warning')
        ->withArgs(fn (string $message, array $context) => $context['looks_truncated'] === false);
});

it('accepts a report with no content length header', function () {
    Log::spy();

    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([
            'content_length_header' => null,
        ]))->assertNoContent();

    Log::shouldHaveReceived('warning')
        ->withArgs(fn (string $message, array $context) => $context['looks_truncated'] === null);
});

it('requires the fields that make a report useful', function (string $field) {
    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([$field => null]))
        ->assertJsonValidationErrors($field);
})->with(['kind', 'page_url', 'status']);

it('refuses a kind outside the known set', function () {
    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload(['kind' => 'lo-que-sea']))
        ->assertJsonValidationErrors('kind');
});

it('refuses a url that is not a url', function (string $field) {
    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([$field => 'no-es-una-url']))
        ->assertJsonValidationErrors($field);
})->with(['page_url', 'request_url']);

it('caps the body excerpts so a report cannot flood the log', function () {
    $this->actingAs(partner())
        ->postJson(route('client_diagnostics.store'), diagnosticPayload([
            'body_head' => str_repeat('a', 1001),
        ]))->assertJsonValidationErrors('body_head');
});

it('throttles a client that reports in a loop', function () {
    Log::spy();

    $user = partner();

    foreach (range(1, 10) as $attempt) {
        $this->actingAs($user)
            ->postJson(route('client_diagnostics.store'), diagnosticPayload())
            ->assertNoContent();
    }

    $this->actingAs($user)
        ->postJson(route('client_diagnostics.store'), diagnosticPayload())
        ->assertStatus(429);
});
