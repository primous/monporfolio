# Rapport d'audit de sécurité — myportfolio

Laravel 12.72 · Filament 5.11 · Livewire 4.4 · PHP 8.2 · MySQL 8 · 20 novembre 2026.
Périmètre : tout le code du dépôt (config, routes, contrôleurs, modèles, migrations, vues, admin Filament).
Le fichier `.env` réel n'a pas été lu.

**Bilan** : aucune faille critique. 4 risques élevés et 7 moyens, liés surtout à la mise en production.
Tous sont corrigés dans le code, sauf ce qui se règle sur le serveur (voir `SECURITE_SERVEUR.md` et `CHECKLIST_MANUELLE.md`).
Tests : 34 → **53 tests, tous verts**. Larastan niveau 5 : **0 erreur**. `composer audit` et `npm audit` : **0 vulnérabilité**.

---

## Vulnérabilités trouvées

| # | Criticité | Problème | Fichier | État |
|---|---|---|---|---|
| E1 | Élevée | `.env`, `.git` et `vendor/` lisibles si la racine du site n'est pas `public/` | structure | ✅ `.htaccess` racine + fichiers cachés refusés (commit 1) · ⚠️ réglage de la racine à faire |
| E2 | Élevée | Réglages de production non imposés (`APP_DEBUG=true` dans l'exemple, logs `debug`, cookie non sécurisé) | `.env.example` | ✅ bloc production documenté, cookie `secure` d'office en production (commits 1 et 4) · ⚠️ `.env` du serveur à remplir |
| E3 | Élevée | Aucune sauvegarde | — | ✅ spatie/laravel-backup chaque nuit, testé (commit 6) · ⚠️ cron + copie hors serveur |
| E4 | Élevée | Aucun en-tête de sécurité (clickjacking possible) | `bootstrap/app.php` | ✅ middleware `SecurityHeaders` + CSP report-only (commit 2) |
| M1 | Moyenne | HTML des articles affiché sans nettoyage (`{!! !!}`) | `resources/views/pages/article.blade.php:78` | ✅ `Str::sanitizeHtml` à l'affichage et dans le résumé (commit 3) |
| M2 | Moyenne | Dossier des téléversements public, sans blocage de PHP ; SVG accepté | `config/filesystems.php`, `ProjetForm.php:35` | ✅ `.htaccess` d'images seules + JPG/PNG/WebP uniquement (commit 3) |
| M3 | Moyenne | Mot de passe admin : 8 caractères minimum | `EditProfile.php` | ✅ 12 caractères, majuscule, minuscule, chiffre, symbole, divulgués refusés en production (commit 4) |
| M4 | Moyenne | Pas de double authentification sur l'admin | `AdminPanelProvider.php` | ✅ application d'authentification + codes de secours, obligatoire en production (commit 9) |
| M5 | Moyenne | Cookie de session non forcé en HTTPS | `config/session.php:172` | ✅ (commit 4) |
| M6 | Moyenne | Proxys non configurés (IP faussée derrière Cloudflare) | `bootstrap/app.php` | ✅ `TRUSTED_PROXIES` réglable, vide par défaut (commit 1) |
| M7 | Moyenne | Secrets de l'ancien `.env` collés dans une conversation | — | ⚠️ à changer toi-même (checklist) |
| F1 | Faible | Validation écrite dans 4 contrôleurs | Avis, Commentaire, Suggestion, Contact | ✅ FormRequest (commit 5) |
| F2 | Faible | Route `/contact` de l'ancien formulaire encore ouverte | `routes/web.php:43` | ✅ piège à robots ajouté · ⏳ à retirer si tu confirmes qu'elle ne sert plus |
| F3 | Faible | Logs `debug` dans un seul fichier | `config/logging.php` | ✅ `LOG_STACK=daily`, `LOG_LEVEL=error` documentés (commit 1) · ⚠️ `.env` du serveur |
| F4 | Faible | E-mail et téléphone des demandes stockés en clair | `app/Models/Demande.php` | ⏸️ non chiffrés volontairement (le chiffrement empêche la recherche dans l'admin) |
| F5 | Faible | Ni `security.txt` ni Dependabot | — | ✅ (commit 7) |

## Contrôles sans anomalie
- **Injection SQL** : un seul `orderByRaw` (`PageController.php:95`), avec paramètre lié. Aucun `DB::raw`, `whereRaw`, `selectRaw`.
- **XSS** : les autres `{!! !!}` sont des icônes SVG écrites dans le code ou du JSON-LD encodé avec `JSON_HEX_TAG`.
- **CSRF** : `@csrf` sur les 3 formulaires HTML, en-tête `X-CSRF-TOKEN` sur les 2 envois `fetch()`, aucune exclusion.
- **Mass assignment** : `$fillable` explicite sur les 10 modèles, aucun `$guarded = []`.
- **Code dangereux** : aucun `exec`, `system`, `eval`, `unserialize`, ni `include` dynamique.
- **Open redirect / SSRF** : toutes les redirections vont vers des routes internes ; aucun appel HTTP sortant.
- **Secrets en dur** : aucun. `.env` est dans `.gitignore` et n'a jamais été commité.
- **Mots de passe** : bcrypt (cast `hashed`), aucun md5/sha1.
- **Connexion** : limitée à 5 essais (Filament), message d'échec identique que l'e-mail existe ou non, session régénérée à la connexion et invalidée à la déconnexion (Filament). Pas d'inscription ni de réinitialisation publique.
- **Admin** : accès vérifié côté serveur (`User::canAccessPanel` : seul `SITE_ADMIN_EMAIL`, refus si vide).
- **Formulaires publics** : 5 envois par 10 minutes, piège à robots.
- **Outils de debug** : Telescope, Debugbar et Ignition absents.

## Non applicable
- **API, Sanctum, CORS, API Resources** : le site n'a pas d'API (pas de `routes/api.php`, pas de `config/cors.php`).
- **IDOR, Policies, isolation entre utilisateurs, multi-tenant** : pas d'espace membre, un seul compte admin qui voit tout par définition. Les tests vérifient qu'un autre compte n'accède à rien (403).
- **MustVerifyEmail** : un seul compte, créé à la main.
- **SRI** : aucun script ni style chargé depuis un CDN (tout est servi par le domaine).
- **Enlightn** : incompatible avec Laravel 12 (s'arrête à Laravel 11). Remplacé par Larastan + les tests de ce rapport.
- **UUID** : les seules ressources publiques sont les articles, déjà adressés par leur slug. Les identifiants numériques ne sont visibles que dans l'admin. Aucun changement recommandé.

## Restant à faire
1. **Passer la CSP en mode bloquant** (`CSP_ENFORCE=true`) après une semaine en ligne sans alerte dans la console du navigateur.
2. **Route `/contact`** : la retirer si tu confirmes qu'elle ne sert plus.
3. Tout ce qui se règle sur le serveur ou dans tes comptes : `SECURITE_SERVEUR.md` et `CHECKLIST_MANUELLE.md`.
4. **Suivi d'erreurs (Sentry ou Flare)** : non branché, en attente de ton accord.

## Commits
| Commit | Étape |
|---|---|
| `b0087c2` | 1/8 Configuration de production (.htaccess racine, fichiers cachés, proxys, `.env.example`) |
| `3c1005f` | 2/8 En-têtes de sécurité et CSP report-only |
| `5461089` | 3/8 XSS des articles et téléversements |
| `f7e2f4d` | 4/8 Règle des mots de passe et cookie de session |
| `dd3a4e7` | 5/8 Validation des formulaires publics en FormRequest |
| `1b3f1db` | 6/8 Sauvegardes automatiques |
| `3af336a` | 7/8 Larastan, Dependabot, security.txt, tests d'accès |
| `cdb7e25` | 8/8 Rapports |
| *(suivant)* | 9 Double authentification de l'admin |

## Commandes au déploiement
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan filament:optimize
php artisan backup:run      # première sauvegarde, pour vérifier
```
