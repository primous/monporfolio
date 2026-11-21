<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité sur toutes les réponses (site public, admin Filament, sitemap…).
 *
 * La Content-Security-Policy est d'abord en mode « report-only » : le navigateur signale
 * dans la console ce qu'elle bloquerait, sans rien bloquer. Une fois la console propre
 * en ligne, passer CSP_ENFORCE=true pour l'appliquer.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = $response->headers;
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()');
        $headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $headers->remove('X-Powered-By');

        // Jamais indexés : admin, Livewire, envois de formulaires, pages d'erreur.
        if ($this->nonIndexable($request, $response)) {
            $headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }

        // HSTS seulement en HTTPS et en production (en local, il bloquerait http://localhost).
        if ($request->isSecure() && app()->isProduction()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $headers->set(
            config('site.csp_enforce') ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only',
            $this->contentSecurityPolicy(),
        );

        return $response;
    }

    private function nonIndexable(Request $request, Response $response): bool
    {
        $admin = trim((string) config('site.admin_path', 'admin'), '/');

        return $response->getStatusCode() >= 400
            || ! $request->isMethodSafe()
            || $request->is($admin, $admin.'/*', 'livewire/*', 'livewire-*', 'filament/*');
    }

    /**
     * Adaptée à ce que le site charge réellement : tout est servi depuis le domaine
     * (build Vite, polices Geist, images). Alpine (via Livewire) évalue les expressions
     * x-data / @click : 'unsafe-eval' est nécessaire. Scripts et styles inline :
     * script de démarrage du layout, Livewire, Filament, attributs style="".
     */
    private function contentSecurityPolicy(): string
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        // En local, Vite (npm run dev) sert les fichiers depuis un autre port.
        if (app()->isLocal() && is_file(public_path('hot'))) {
            $vite = rtrim((string) file_get_contents(public_path('hot')));
            $ws = preg_replace('#^http#', 'ws', $vite);
            $directives[1] .= " {$vite}";
            $directives[2] .= " {$vite}";
            $directives[5] .= " {$vite} {$ws}";
        }

        return implode('; ', $directives);
    }
}
