<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sauvegardes (spatie/laravel-backup) : base de données + images téléversées, chaque nuit.
// Nécessite la tâche cron du serveur : * * * * * php artisan schedule:run (voir SECURITE_SERVEUR.md).
Schedule::command('backup:clean')->dailyAt('01:00')->withoutOverlapping();
Schedule::command('backup:run')->dailyAt('01:30')->withoutOverlapping()
    // Jamais d'archive non chiffrée envoyée hors du serveur.
    ->when(function (): bool {
        $externe = in_array('sauvegarde_externe', (array) config('backup.backup.destination.disks'), true);
        if ($externe && blank(config('backup.backup.password'))) {
            \Illuminate\Support\Facades\Log::critical('Sauvegarde annulée : copie hors serveur demandée sans BACKUP_ARCHIVE_PASSWORD.');

            return false;
        }

        return true;
    });
Schedule::command('backup:monitor')->dailyAt('03:00');
