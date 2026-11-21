<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        // Pas de route /up : sa réponse « Application up » signale un site Laravel aux robots.
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Laravel 12 : plus de Kernel.php, la pile globale se règle ici. Ordre d'exécution :
        // 1) SecurityHeaders (le plus à l'extérieur : ses en-têtes couvrent aussi les 403/404 des robots) ;
        // 2) DetecteurDeScans : .env, .git, wp-admin… journalisés, IP bloquée 24 h au-delà de 10 essais en 5 min.
        $middleware->prepend([
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\DetecteurDeScans::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
