<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // El contenedor Docker local ya trae DB_CONNECTION/DB_DATABASE como
        // variables de entorno reales del proceso, así que los <env> de
        // phpunit.xml no alcanzan a pisarlas (Laravel prioriza $_ENV/$_SERVER
        // por sobre lo que PHPUnit define via putenv(), incluso con force="true").
        // Sin esto, RefreshDatabase corre contra la base de datos real de
        // desarrollo en vez de sqlite en memoria.
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        return $app;
    }
}
