<?php

/*
|--------------------------------------------------------------------------
| Google Tag Manager
|--------------------------------------------------------------------------
|
| GTM se carga en las páginas públicas para medir conversiones de la landing,
| pero nunca en el dashboard ni en las pantallas de acceso: ahí navegan admins
| y partners, y su tráfico no debe ensuciar las métricas.
|
*/

beforeEach(function () {
    seedStatuses();
    config(['services.gtm.id' => 'GTM-TEST123']);
});

it('loads GTM on the landing', function () {
    $this->get(route('landing'))
        ->assertOk()
        ->assertSee('googletagmanager.com/gtm.js', false)
        ->assertSee("'GTM-TEST123'", false)
        ->assertSee('googletagmanager.com/ns.html?id=GTM-TEST123', false);
});

it('does not load GTM when no container id is configured', function () {
    config(['services.gtm.id' => null]);

    $this->get(route('landing'))
        ->assertOk()
        ->assertDontSee('googletagmanager.com', false);
});

it('does not load GTM on the login screen', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertDontSee('googletagmanager.com', false);
});

it('does not load GTM on the dashboard', function () {
    $this->actingAs(admin())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('googletagmanager.com', false);
});
