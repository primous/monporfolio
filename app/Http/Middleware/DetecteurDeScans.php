<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Repère les robots qui cherchent des fichiers sensibles (/.env, /.git, /wp-admin, /phpmyadmin…),
 * c'est-à-dire ce que trouvent les « Google Dorks ».
 *
 * - Chaque tentative : journalisée (canal « securite », storage/logs/securite-*.log), réponse 404.
 * - Plus de 10 tentatives en 5 minutes depuis une même IP : log « critical » et IP bloquée 24 h
 *   (403 sur tout le site).
 *
 * Placé en tête de la pile : aucune session ni base de données n'est ouverte pour un robot.
 */
class DetecteurDeScans
{
    /** Chemins qu'aucun visiteur légitime ne demande sur ce site. */
    private const MOTIFS = [
        '#(^|/)\.(?!well-known(/|$))#i',                       // fichiers cachés : .env, .git, .aws, .DS_Store, .htpasswd…
        '#(^|/)(wp-admin|wp-login\.php|wp-content|wp-includes|wp-config|xmlrpc\.php)#i',
        '#(^|/)(phpmyadmin|pma|myadmin|mysqladmin|adminer)(\.php)?(/|$)#i',
        '#(^|/)(actuator|server-status|server-info|phpinfo|info\.php|test\.php|debug|telescope|horizon|pulse|_ignition|_debugbar)(/|$|\.php)#i',
        '#(^|/)(vendor|storage|config|database|bootstrap|backup|backups|cgi-bin)(/|$)#i',
        '#(^|/)(id_rsa|id_dsa|composer\.(json|lock)|package\.json|artisan|web\.config)$#i',
        '#\.(sql|bak|old|orig|swp|save|backup|tar|gz|tgz|zip|rar|7z|log|ini|conf|yml|yaml|env|pem|key|sqlite)$#i',
    ];

    private const SEUIL = 10;              // tentatives…

    private const FENETRE = 300;           // …en 5 minutes

    private const DUREE_BLOCAGE = 86400;   // 24 heures

    public function handle(Request $request, Closure $next): Response
    {
        $ip = (string) $request->ip();
        $chemin = ltrim($request->path(), '/');

        if (Cache::has("scan:bloque:{$ip}")) {
            return response('Accès refusé.', 403, ['Content-Type' => 'text/plain; charset=UTF-8', 'X-Robots-Tag' => 'noindex, nofollow']);
        }

        if (! $this->estUnScan($chemin)) {
            return $next($request);
        }

        Cache::add("scan:compte:{$ip}", 0, self::FENETRE);
        $tentatives = (int) Cache::increment("scan:compte:{$ip}");

        $contexte = [
            'ip' => $ip,
            'methode' => $request->method(),
            'chemin' => Str::limit('/'.$chemin, 200),
            'navigateur' => Str::limit((string) $request->userAgent(), 160),
            'tentatives_5min' => $tentatives,
        ];

        // Jamais de blocage pour le serveur lui-même (127.0.0.1, ::1) : journalisé seulement.
        $local = \Symfony\Component\HttpFoundation\IpUtils::checkIp($ip, ['127.0.0.0/8', '::1']);

        if ($tentatives > self::SEUIL && ! $local) {
            Cache::put("scan:bloque:{$ip}", true, self::DUREE_BLOCAGE);
            Log::channel('securite')->critical('Scan de fichiers sensibles : IP bloquée 24 h', $contexte);
        } else {
            Log::channel('securite')->warning('Tentative d\'accès à un chemin sensible', $contexte);
        }

        abort(404);
    }

    private function estUnScan(string $chemin): bool
    {
        // L'ancienne adresse de l'admin devient un leurre dès qu'ADMIN_PATH est personnalisée.
        $admin = trim((string) config('site.admin_path', 'admin'), '/');
        if ($admin !== 'admin' && preg_match('#^admin(/|$)#i', $chemin)) {
            return true;
        }

        foreach (self::MOTIFS as $motif) {
            if (preg_match($motif, $chemin)) {
                return true;
            }
        }

        return false;
    }
}
