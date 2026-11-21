<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Liste d'IP autorisées pour l'admin (ADMIN_ALLOWED_IPS, virgules, CIDR accepté).
 * Vide = désactivé (par défaut) : utile seulement si ton adresse IP est fixe.
 * Une IP non autorisée reçoit une 404 : l'admin semble ne pas exister.
 */
class RestreindreAdminParIp
{
    public function handle(Request $request, Closure $next): Response
    {
        $liste = array_values(array_filter(array_map('trim', explode(',', (string) config('site.admin_allowed_ips')))));

        if ($liste !== [] && ! IpUtils::checkIp((string) $request->ip(), $liste)) {
            Log::channel('securite')->warning('Admin ouverte depuis une IP non autorisée', ['ip' => $request->ip(), 'chemin' => '/'.$request->path()]);
            abort(404);
        }

        return $next($request);
    }
}
