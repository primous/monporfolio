<?php

namespace App\Actions;

use App\Models\ContactMessage;
use App\Models\Demande;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Droit à l'effacement (RGPD, art. 17 ; loi béninoise n° 2017-20) : supprime toutes les données
 * rattachées à une adresse e-mail (demandes de projet, messages de contact).
 *
 * Déclenché uniquement par l'admin (action « Effacer les données d'une personne » ou
 * `php artisan donnees:effacer`), jamais par une route publique : sinon n'importe qui
 * pourrait effacer les données d'un autre. Les avis et commentaires ne portent pas d'e-mail :
 * ils se suppriment un par un dans l'admin.
 */
class EffacerDonneesPersonne
{
    /** @return array{demandes: int, messages: int} */
    public function __invoke(string $email): array
    {
        $email = mb_strtolower(trim($email));

        $resultat = DB::transaction(fn (): array => [
            'demandes' => Demande::query()->whereRaw('LOWER(email) = ?', [$email])->delete(),
            'messages' => ContactMessage::query()->whereRaw('LOWER(email) = ?', [$email])->delete(),
        ]);

        // Trace de l'effacement sans conserver l'adresse (empreinte seulement).
        Log::info('Effacement RGPD effectué', [...$resultat, 'empreinte_email' => substr(hash('sha256', $email), 0, 16)]);

        return $resultat;
    }

    /** @return array{demandes: int, messages: int} */
    public function compter(string $email): array
    {
        $email = mb_strtolower(trim($email));

        return [
            'demandes' => Demande::query()->whereRaw('LOWER(email) = ?', [$email])->count(),
            'messages' => ContactMessage::query()->whereRaw('LOWER(email) = ?', [$email])->count(),
        ];
    }
}
