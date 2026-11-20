<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // En ligne, toutes les URL générées (canonical, sitemap, Open Graph) sont en HTTPS.
        if ($this->app->isProduction()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Règle des mots de passe (page « Mon profil » de l'admin) : 12 caractères, majuscule, minuscule,
        // chiffre, symbole. En production, refus des mots de passe déjà divulgués (Have I Been Pwned :
        // seuls les 5 premiers caractères de l'empreinte SHA-1 sont envoyés, jamais le mot de passe).
        \Illuminate\Validation\Rules\Password::defaults(function () {
            $regle = \Illuminate\Validation\Rules\Password::min(12)->letters()->mixedCase()->numbers()->symbols();

            return $this->app->isProduction() ? $regle->uncompromised() : $regle;
        });

        // Derrière un proxy (Cloudflare, répartiteur Hostinger) : IP réelle du visiteur et HTTPS détectés.
        // TRUSTED_PROXIES vide = aucun proxy de confiance (valeur par défaut, la plus sûre).
        $proxies = config('site.trusted_proxies');
        if (filled($proxies)) {
            \Illuminate\Http\Middleware\TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }
    }
}
