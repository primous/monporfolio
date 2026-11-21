# Sécurité — hounkpatinprimous.com

Laravel 12 · Filament 5 · Livewire 4 · Blade · MySQL 8 · Apache (Hostinger) ou Nginx.
Dernière revue : 21 novembre 2026.

Signaler une faille : `/.well-known/security.txt` (formulaire du site ou e-mail indiqué).
Documents liés : [docs/RAPPORT_AUDIT.md](docs/RAPPORT_AUDIT.md) (audit du 20/11), [docs/SECURITE_SERVEUR.md](docs/SECURITE_SERVEUR.md) (configurations serveur), [docs/CHECKLIST_MANUELLE.md](docs/CHECKLIST_MANUELLE.md) (actions manuelles).

---

## 1. Vulnérabilités trouvées avant correction (audit anti-Google Dorking du 21/11/2026)

| # | Constat | Dork Google qui l'aurait trouvé | Correction |
|---|---|---|---|
| 1 | Admin à l'adresse par défaut `/admin/login` (Filament reconnaissable) | `inurl:admin/login intitle:"Connexion"` | Adresse réglable `ADMIN_PATH` ; `/admin` = leurre 404 journalisé |
| 2 | `robots.txt` affichait `Disallow: /admin` (carte pour les curieux) | `inurl:robots.txt "Disallow: /admin"` | Leurres seulement, jamais la vraie adresse ; `X-Robots-Tag: noindex` sur l'admin |
| 3 | Scans de `/.env`, `/.git`, `/wp-admin` ni journalisés ni bloqués | `inurl:.env "DB_PASSWORD"` · `intitle:"index of" ".git"` | `DetecteurDeScans` : 404 + journal ; > 10 en 5 min → log critical + IP bloquée 24 h |
| 4 | E-mail en clair à 5 endroits (pied de page, pages légales, JSON-LD) | `"@hounkpatinprimous.com"` | Composant `x-ui.email-protege` (révélé au clic), retiré du JSON-LD |
| 5 | Connexion admin : 5 essais par minute seulement | — | 5 échecs par 15 min et par IP, échecs journalisés |
| 6 | Pas de déconnexion de l'admin après inactivité | — | Déconnexion après 15 min (`ADMIN_IDLE_MINUTES`) |
| 7 | Sauvegardes seulement sur le serveur | — | Disque `sauvegarde_externe` (S3/B2/R2), archives chiffrées obligatoires |
| 8 | Compte « Test User » créé par `db:seed` même en production | — | Seulement en local et en test |
| 9 | Pas de procédure de droit à l'effacement | — | Action admin + `php artisan donnees:effacer` |
| 10 | `X-Frame-Options: SAMEORIGIN`, CSP en simple signalement | — | `DENY`, `frame-ancestors 'none'`, CSP appliquée sur le site public |
| 11 | Pages d'erreur Laravel par défaut (« Not Found ») | `intitle:"Not Found" "Laravel"` | Pages 403/404/419/429/500/503 personnalisées, `noindex` |
| 12 | Route `/up` « Application up » (signature Laravel) | `inurl:/up "Application up"` | Route retirée |
| 13 | `.gitignore` sans `*.sql`, `*.bak`, `*.zip` | `intitle:"index of" "backup.sql"` | Ajoutés |
| 14 | `X-Powered-By: PHP/8.x` envoyé par PHP | `"X-Powered-By: PHP"` (moteurs comme Shodan) | Retiré (PHP, Laravel et Apache) |

Déjà conformes avant cet audit (vérifiés) : aucun `.env`/`.sql`/`.bak`/`phpinfo` dans `public/`, aucune route de debug, Telescope/Debugbar/Horizon/Pulse absents, aucun secret en dur, logs sans secret, requêtes SQL liées, `@csrf` partout, 2FA admin, mot de passe fort, HTTPS forcé, cookie de session sécurisé, `$hidden` sur les champs sensibles.

Sans objet : pas d'API (`throttle:60,1` sur `api/*`, Sanctum, CORS, Swagger), pas d'inscription ni d'espace client, pas de Breeze/Fortify/Jetstream (authentification Filament).

---

## 2. Fichiers modifiés ou créés (anti-dorking)

**Créés**
- `app/Http/Middleware/DetecteurDeScans.php` — journalise et bloque les scans
- `app/Http/Middleware/DeconnexionInactivite.php` — déconnexion admin après 15 min
- `app/Http/Middleware/RestreindreAdminParIp.php` — liste d'IP (option)
- `app/Filament/Pages/Auth/Login.php` — 5 échecs / 15 min, échecs journalisés
- `app/Actions/EffacerDonneesPersonne.php`, `app/Console/Commands/EffacerDonnees.php` — droit à l'effacement
- `resources/views/components/ui/email-protege.blade.php` — e-mail protégé
- `resources/views/errors/*.blade.php` — pages d'erreur (layout, 403, 404, 419, 429, 500, 503)
- `scripts/verifier-securite.sh` — vérification automatique en ligne
- `tests/Feature/AntiDorkingTest.php`, `AdminDiscreteTest.php`, `AdminPathPersonnaliseTest.php`, `DiscretionTest.php`, `DonneesPersonnellesTest.php`
- `SECURITY.md` (ce fichier)

**Modifiés**
- `bootstrap/app.php` — ordre des middlewares globaux, route `/up` retirée
- `app/Http/Middleware/SecurityHeaders.php` — DENY, CSP appliquée, X-Robots-Tag, X-Powered-By
- `app/Providers/Filament/AdminPanelProvider.php` — `ADMIN_PATH`, connexion, middlewares
- `app/Http/Controllers/SeoController.php` — robots.txt
- `app/Filament/Resources/Demandes/Pages/ListDemandes.php` — action d'effacement
- `config/site.php`, `config/logging.php` (canal `securite`), `config/filesystems.php` (`sauvegarde_externe`)
- `routes/console.php` — sauvegarde refusée si non chiffrée hors serveur
- `database/seeders/DatabaseSeeder.php` — pas de compte de test en production
- `public/.htaccess` — fichiers cachés envoyés à Laravel, en-têtes sur les fichiers statiques
- `resources/views/components/layout/footer.blade.php`, `pages/mentions-legales.blade.php`, `pages/politique-confidentialite.blade.php`, `seo/schema-site.blade.php` — e-mails
- `.gitignore`, `.env.example`, `composer.json` / `composer.lock` (`league/flysystem-aws-s3-v3`)
- `tests/TestCase.php` (journal muet en test), `tests/Feature/SecurityHeadersTest.php`

---

## 3. Réglages de production (`.env` du serveur)

```dotenv
APP_ENV=production
APP_DEBUG=false                 # jamais true en ligne
APP_URL=https://hounkpatinprimous.com
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
LOG_STACK=daily
LOG_LEVEL=error

ADMIN_PATH=                     # adresse discrète de l'admin, ex. gestion-xxxxxxxxxx (jamais « admin »)
ADMIN_ALLOWED_IPS=              # vide = pas de restriction ; sinon IP fixes / CIDR
ADMIN_IDLE_MINUTES=15
CSP_ENFORCE=true
CSP_ENFORCE_ADMIN=false         # true après une semaine sans alerte CSP dans la console de l'admin

BACKUP_ARCHIVE_PASSWORD=        # obligatoire dès que la copie hors serveur est active
BACKUP_DISKS=backups,sauvegarde_externe
BACKUP_S3_KEY= / BACKUP_S3_SECRET= / BACKUP_S3_REGION= / BACKUP_S3_BUCKET= / BACKUP_S3_ENDPOINT=
```

Pas de `SANCTUM_STATEFUL_DOMAINS` ni de `config/cors.php` : le site n'a pas d'API.

**Déploiement**

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan filament:optimize
```

Après tout changement de `ADMIN_PATH` : relancer `php artisan route:cache`.

**Serveur** : racine du site = `public/` (voir `docs/SECURITE_SERVEUR.md` §1). Sous Nginx, pour que les scans soient journalisés par Laravel plutôt que refusés en silence :

```nginx
location ~ /\.(?!well-known) { rewrite ^ /index.php last; }
```

---

## 4. Commandes de vérification

Script complet (≈ 35 contrôles) :

```bash
bash scripts/verifier-securite.sh https://hounkpatinprimous.com gestion-xxxxxxxxxx
```

⚠️ Il demande ~25 chemins sensibles : ton IP sera bloquée 24 h par le détecteur. Lance-le en données mobiles, ou débloque-toi sur le serveur :
`php artisan tinker --execute='Cache::forget("scan:bloque:TON.IP"); Cache::forget("scan:compte:TON.IP");'`

Contrôles manuels :

```bash
curl -I https://hounkpatinprimous.com/.env                      # 404 ou 403
curl -I https://hounkpatinprimous.com/.git/HEAD                 # 404 ou 403
curl -I https://hounkpatinprimous.com/storage/logs/laravel.log  # 404 ou 403
curl -I https://hounkpatinprimous.com/vendor/autoload.php       # 404 ou 403
curl -I https://hounkpatinprimous.com/backup.sql                # 404 ou 403
curl -I https://hounkpatinprimous.com/admin/login               # 404 (leurre)
curl -I https://hounkpatinprimous.com/                          # Content-Security-Policy, Strict-Transport-Security, X-Frame-Options: DENY
curl -I https://hounkpatinprimous.com/gestion-xxxxxxxxxx/login  # 200 + X-Robots-Tag: noindex
curl -s https://hounkpatinprimous.com/robots.txt                # leurres, jamais gestion-xxxxxxxxxx
curl -s https://hounkpatinprimous.com/ | grep -c "@hounkpatinprimous"   # 0
```

Tests automatiques (local, 100 tests) : `php artisan test` · analyse statique : `vendor/bin/phpstan analyse`.

Contrôles externes : https://securityheaders.com (objectif A) · https://www.ssllabs.com/ssltest/ (objectif A) · https://observatory.mozilla.org.

---

## 5. Checklist mensuelle (≈ 20 minutes, le 1er du mois)

- [ ] `bash scripts/verifier-securite.sh …` : 0 point à corriger.
- [ ] `php artisan test` en local : tout vert.
- [ ] Journal des scans : `storage/logs/securite-*.log` — combien de tentatives, d'IP bloquées (`CRITICAL`), d'échecs de connexion admin ? Un pic d'échecs sur ton e-mail = attaque ciblée : change le mot de passe.
- [ ] `storage/logs/laravel.log` : aucune erreur répétée, aucun mot de passe ni jeton.
- [ ] Sauvegardes : `php artisan backup:list` (dernière < 24 h, copie hors serveur présente). **Tous les 3 mois** : restauration testée (`docs/CHECKLIST_MANUELLE.md` §6).
- [ ] Dépendances : `composer audit` et `npm audit` (0 vulnérabilité) ; fusionner les pull requests Dependabot.
- [ ] Google : recherches `site:hounkpatinprimous.com` (aucune page d'admin, d'erreur ou de fichier technique indexée), `"@hounkpatinprimous.com"`, `site:hounkpatinprimous.com ext:env OR ext:sql OR ext:log OR ext:bak` (aucun résultat).
- [ ] Search Console → Pages : aucune URL inattendue indexée (supprimer via « Suppressions » si besoin).
- [ ] Console du navigateur (F12) sur l'accueil, un article, la demande de projet et l'admin : aucune alerte « Content Security Policy ».
- [ ] Admin : 2FA toujours active, aucun compte inconnu dans la table `users`.
- [ ] `public/.well-known/security.txt` : date `Expires` dans le futur (renouveler avant le 20/11/2027).

---

## 6. Limites connues
- **CSP** : `'unsafe-inline'` et `'unsafe-eval'` restent nécessaires. Alpine (embarqué par Livewire) évalue les expressions écrites dans les attributs (`x-data`, `@click`), et Filament comme les formulaires du site en dépendent. Le mode `csp_safe` de Livewire imposerait de réécrire tous ces composants. Les autres directives sont strictes (`default-src 'self'`, aucun domaine tiers, `object-src 'none'`, `frame-ancestors 'none'`).
- **Captures des projets** dans `public/images/uploads` (et non `storage/app/private`) : elles doivent être publiques pour s'afficher. Le dossier n'accepte que JPG/PNG/WebP, n'exécute aucun script et n'est pas listable.
- **Blocage d'IP** en cache applicatif : il ne remplace pas un WAF. Cloudflare (gratuit) est recommandé en plus (`docs/CHECKLIST_MANUELLE.md` §5) ; penser alors à `TRUSTED_PROXIES=*`.
- **Robots déjà passés** : une page déjà indexée par Google ne disparaît pas seule. Vérifier `site:` chaque mois et demander la suppression dans Search Console.
