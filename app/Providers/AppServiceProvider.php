<?php

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mime\Address;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // MAIL_FORCE_FROM_ADDRESS fija el remitente de TODO mensaje, no sólo de
        // los que pasan por SenderIdentity. Se resuelve al enviar (no en boot)
        // para leer la config vigente. El nombre visible se conserva.
        Event::listen(MessageSending::class, function (MessageSending $event): void {
            $forced = config('mail.force_from_address');

            if (! filled($forced)) {
                return;
            }

            $current = $event->message->getFrom()[0] ?? null;
            $name = $current?->getName() ?: (string) config('mail.from.name');

            $event->message->from(new Address((string) $forced, $name));
        });
    }
}
