<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sauvegardes (spatie/laravel-backup) : base de données + images téléversées, chaque nuit.
// Nécessite la tâche cron du serveur : * * * * * php artisan schedule:run (voir SECURITE_SERVEUR.md).
Schedule::command('backup:clean')->dailyAt('01:00')->withoutOverlapping();
Schedule::command('backup:run')->dailyAt('01:30')->withoutOverlapping();
Schedule::command('backup:monitor')->dailyAt('03:00');
