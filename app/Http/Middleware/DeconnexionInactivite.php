<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin : déconnexion automatique après ADMIN_IDLE_MINUTES (15 par défaut) sans activité.
 * Le site public n'est pas concerné (un visiteur qui écrit un long avis ne perd pas son texte).
 */
class DeconnexionInactivite
{
    private const CLE = 'admin_derniere_activite';

    public function handle(Request $request, Closure $next): Response
    {
        $garde = Filament::auth();
        $minutes = max(1, (int) config('site.admin_idle_minutes', 15));

        if ($garde->check()) {
            $derniere = (int) $request->session()->get(self::CLE, 0);

            if ($derniere > 0 && (now()->getTimestamp() - $derniere) > $minutes * 60) {
                $garde->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $connexion = Filament::getLoginUrl();

                return $request->expectsJson() || $request->hasHeader('X-Livewire')
                    ? response()->json(['message' => 'Session expirée.'], 419) // 419 : Livewire propose de recharger la page, qui mène à la connexion
                    : redirect()->to($connexion);
            }

            $request->session()->put(self::CLE, now()->getTimestamp());
        }

        return $next($request);
    }
}
