<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Connexion à l'admin, avec une limite plus stricte que celle de Filament :
 * - Filament : 5 essais par minute (conservé) ;
 * - ici : 5 ÉCHECS par 15 minutes et par IP, puis connexion refusée jusqu'à la fin du délai.
 * Chaque échec est journalisé (canal « securite »), sans jamais écrire le mot de passe.
 */
class Login extends BaseLogin
{
    private const ECHECS_MAX = 5;

    private const FENETRE = 900; // 15 minutes

    public function authenticate(): ?LoginResponse
    {
        $cle = $this->cleEchecs();

        if (RateLimiter::tooManyAttempts($cle, self::ECHECS_MAX)) {
            $this->getRateLimitedNotification(new TooManyRequestsException(
                static::class, 'authenticate', request()->ip(), RateLimiter::availableIn($cle),
            ))?->send();

            return null;
        }

        $reponse = parent::authenticate();

        if ($reponse !== null) {
            RateLimiter::clear($cle); // connexion réussie (mot de passe + 2FA) : compteur remis à zéro
        }

        return $reponse;
    }

    protected function throwFailureValidationException(): never
    {
        RateLimiter::hit($this->cleEchecs(), self::FENETRE);

        Log::channel('securite')->warning('Échec de connexion à l\'admin', [
            'ip' => request()->ip(),
            // E-mail partiellement masqué : utile pour repérer une attaque ciblée, inutile à un voleur de logs.
            'email' => Str::mask((string) data_get($this->data, 'email'), '*', 2, -6),
            'echecs_15min' => RateLimiter::attempts($this->cleEchecs()),
        ]);

        parent::throwFailureValidationException();
    }

    private function cleEchecs(): string
    {
        return 'admin-connexion-echecs:'.sha1((string) request()->ip());
    }
}
