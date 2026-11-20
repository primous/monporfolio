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

        // Derrière un proxy (Cloudflare, répartiteur Hostinger) : IP réelle du visiteur et HTTPS détectés.
        // TRUSTED_PROXIES vide = aucun proxy de confiance (valeur par défaut, la plus sûre).
        $proxies = config('site.trusted_proxies');
        if (filled($proxies)) {
            \Illuminate\Http\Middleware\TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }
    }
}
