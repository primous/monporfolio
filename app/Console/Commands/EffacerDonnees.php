<?php

namespace App\Console\Commands;

use App\Actions\EffacerDonneesPersonne;
use Illuminate\Console\Command;

/** Droit à l'effacement en ligne de commande : php artisan donnees:effacer personne@exemple.com */
class EffacerDonnees extends Command
{
    protected $signature = 'donnees:effacer {email : adresse e-mail de la personne} {--force : sans confirmation}';

    protected $description = 'Supprime les demandes de projet et messages de contact liés à une adresse e-mail (droit à l\'effacement)';

    public function handle(EffacerDonneesPersonne $effacer): int
    {
        $email = (string) $this->argument('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Adresse e-mail invalide.');

            return self::INVALID;
        }

        $trouve = $effacer->compter($email);
        $this->info("Trouvé : {$trouve['demandes']} demande(s), {$trouve['messages']} message(s).");

        if (array_sum($trouve) === 0) {
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Supprimer définitivement ces données ?')) {
            $this->line('Rien n\'a été supprimé.');

            return self::SUCCESS;
        }

        $fait = $effacer($email);
        $this->info("Supprimé : {$fait['demandes']} demande(s), {$fait['messages']} message(s).");

        return self::SUCCESS;
    }
}
