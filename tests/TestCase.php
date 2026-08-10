<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        // El contenedor Docker inyecta APP_ENV, DB_*, CACHE_STORE, MAIL_MAILER,
        // QUEUE_CONNECTION y SESSION_DRIVER como variables de entorno REALES del
        // proceso (docker-compose), no sólo vía .env. Laravel prioriza
        // $_ENV/$_SERVER por sobre lo que PHPUnit define con putenv(), así que
        // TODO el bloque <php><env> de phpunit.xml queda inerte dentro del
        // contenedor, incluso con force="true".
        //
        // Por eso la configuración de testing se fuerza acá, después del
        // bootstrap: es el único punto que gana siempre.
        //
        // APP_ENV va antes del bootstrap porque $app->runningUnitTests() se
        // resuelve durante el arranque; sin esto VerifyCsrfToken no se saltea y
        // todo POST/PUT/DELETE responde 419.
        $_ENV['APP_ENV'] = 'testing';
        $_SERVER['APP_ENV'] = 'testing';
        putenv('APP_ENV=testing');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        config([
            // Sin esto RefreshDatabase corre contra la base real de desarrollo
            // y la vacía por completo. Ver el incidente documentado en engram.
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',

            // En el contenedor CACHE_STORE=database: la cache buscaría la tabla
            // `cache`, que no existe en la sqlite en memoria.
            'cache.default' => 'array',

            // Crítico: sin esto los tests enviarían correo de verdad por el
            // mailer configurado en el contenedor.
            'mail.default' => 'array',

            // Los jobs se ejecutan en el momento salvo que el test use
            // Queue::fake(); nunca se encolan en la tabla `jobs`.
            'queue.default' => 'sync',

            'session.driver' => 'array',
        ]);

        // FormSubmissionStatus::getIdByName() cachea ids con rememberForever.
        // La base se resetea entre tests, así que un id cacheado sobreviviente
        // apuntaría a una fila inexistente.
        Cache::flush();

        return $app;
    }
}
