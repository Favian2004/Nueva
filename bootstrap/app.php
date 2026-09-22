<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/acceso');

        // Confía en el proxy de Hostinger para que Laravel detecte bien
        // que las peticiones llegan por HTTPS (esto evita fallos raros e
        // intermitentes con cosas que dependen de la sesión/cookies,
        // como el inicio de sesión con Google).
        $middleware->trustProxies(at: '*');

        // El webhook de Mercado Pago llega directo desde sus servidores,
        // sin pasar por ningún formulario nuestro, así que nunca va a traer
        // el token de seguridad (_token) — lo excluimos para que no le dé
        // un error 419 y bloquee la confirmación del pago.
        $middleware->validateCsrfTokens(except: [
            'anunciar/webhook',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'not-admin' => \App\Http\Middleware\RedirectIfAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
