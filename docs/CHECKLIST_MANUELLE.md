# Checklist manuelle — ce que tu dois faire toi-même

À cocher dans l'ordre. Rien de ceci ne peut être fait depuis le code.

## 1. Secrets à changer (urgent, avant la mise en ligne)
Des valeurs de l'ancien `.env` de monportfolio (mot de passe de la base, etc.) ont été collées dans une conversation. Considère-les comme exposées.
- [ ] Changer le **mot de passe MySQL** utilisé par l'ancien projet, et créer un utilisateur MySQL dédié pour le site en ligne (`SECURITE_SERVEUR.md` §7).
- [ ] Changer le **mot de passe de la boîte mail** Hostinger `contact@hounkpatinprimous.com`, puis le mettre dans `MAIL_PASSWORD` du `.env` du serveur.
- [ ] Changer tout autre mot de passe ou clé présent dans cet ancien `.env` (Gmail, clés d'API…).
- [ ] Générer une **nouvelle `APP_KEY`** sur le serveur : `php artisan key:generate` (une clé propre à la production, jamais celle du local).
- [ ] Changer le **mot de passe admin** du site après la mise en ligne (page « Mon profil ») : 12 caractères minimum, majuscule, minuscule, chiffre, symbole.
- [ ] Ranger le `.env` de production dans un gestionnaire de mots de passe (Bitwarden, 1Password…) : il n'est pas dans les sauvegardes.

## 2. `.env` du serveur
Copier `.env.example`, puis régler à la main (bloc « EN PRODUCTION » en bas du fichier) :
- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://hounkpatinprimous.com`, `APP_LOCALE=fr`
- [ ] `LOG_STACK=daily`, `LOG_LEVEL=error`
- [ ] `SESSION_SECURE_COOKIE=true`, `SESSION_LIFETIME=60`
- [ ] `DB_*` : utilisateur MySQL dédié (pas root)
- [ ] `SITE_ADMIN_EMAIL`, `SITE_NOTIFY_EMAIL`, `SITE_EMAIL`, `SITE_PHONE`, `SITE_WHATSAPP`
- [ ] `BACKUP_ARCHIVE_PASSWORD` : mot de passe long (chiffre les archives de sauvegarde), à garder dans ton gestionnaire
- [ ] `BACKUP_NOTIFY_EMAIL` (sinon `SITE_NOTIFY_EMAIL`)
- [ ] `DB_DUMP_PATH` : seulement si `mysqldump` n'est pas trouvé (en local : `C:/Program Files/MySQL/MySQL Server 8.0/bin`)
- [ ] `TRUSTED_PROXIES=*` : seulement si tu actives Cloudflare
- [ ] `chmod 600 .env`

## 3. Double authentification (2FA) partout
- [ ] **Hostinger** (hPanel → Profil → Sécurité → 2FA)
- [ ] **GitHub** (Settings → Password and authentication → 2FA) ; dépôt `primous/monporfolio` : vérifier qu'il est **privé** s'il ne doit pas être public
- [ ] **Registrar du domaine** (si différent d'Hostinger) + **verrou de transfert** activé
- [ ] **Compte Google** (Search Console, Google Business Profile)
- [ ] **Boîte mail Hostinger**
- [ ] **Admin du site** : après validation de la migration 2FA (voir RAPPORT_AUDIT.md)

## 4. E-mail : SPF, DKIM, DMARC
hPanel → Emails → `hounkpatinprimous.com` → Authentification / DNS. Hostinger propose souvent de les ajouter en un clic. Sinon, enregistrements DNS :

| Type | Nom | Valeur |
|---|---|---|
| TXT | `@` | `v=spf1 include:_spf.mail.hostinger.com ~all` |
| CNAME / TXT | `hostingermail1._domainkey` (etc.) | valeurs DKIM données par hPanel |
| TXT | `_dmarc` | `v=DMARC1; p=none; rua=mailto:contact@hounkpatinprimous.com; fo=1` |

- [ ] Après 2 à 4 semaines sans problème dans les rapports DMARC : passer à `p=quarantine`.
- [ ] Vérifier avec https://www.mail-tester.com (objectif 9/10 ou plus).

## 5. Cloudflare / WAF (recommandé, gratuit)
- [ ] Créer un compte Cloudflare, ajouter le domaine, changer les serveurs DNS chez le registrar.
- [ ] SSL/TLS : mode **Full (strict)** ; « Always Use HTTPS » activé.
- [ ] Security → WAF : activer les règles gérées gratuites ; Bot Fight Mode activé.
- [ ] Règle de limite : `/admin/login`, 10 requêtes par minute et par IP.
- [ ] Mettre `TRUSTED_PROXIES=*` dans le `.env` du serveur (sinon toutes les IP vues seront celles de Cloudflare et les limites d'envoi s'appliqueront à tout le monde à la fois).
- [ ] Ne pas mettre en cache `/admin*` ni `/livewire*` (règle « Bypass cache »).

## 6. Sauvegardes
- [ ] Ajouter la tâche cron `schedule:run` (`SECURITE_SERVEUR.md` §3).
- [ ] Lancer une première sauvegarde : `php artisan backup:run`, puis `php artisan backup:list`.
- [ ] **Copie hors du serveur** : une fois par semaine, télécharger la dernière archive (`storage/app/backups/myportfolio/`) sur ton ordinateur ou Google Drive. Une sauvegarde qui reste sur le serveur disparaît avec lui. (Option : ajouter un disque S3 / Backblaze B2 et mettre `BACKUP_DISKS=backups,s3`.)
- [ ] **Tester la restauration**, au moins une fois par trimestre, sur ta machine :
  1. Dézipper l'archive (avec `BACKUP_ARCHIVE_PASSWORD`).
  2. Créer une base vide `myportfolio_test` et importer `db-dumps/mysql-myportfolio.sql`.
  3. Pointer un `.env` local sur `myportfolio_test`, copier `images/uploads/` dans `public/images/uploads/`, ouvrir le site et l'admin.
  4. Supprimer la base de test.

## 7. Après la mise en ligne
- [ ] Vérifier que `/.env`, `/.git/config`, `/vendor/autoload.php` renvoient 403 ou 404 (`SECURITE_SERVEUR.md` §1).
- [ ] https://securityheaders.com → objectif A.
- [ ] https://www.ssllabs.com/ssltest/ → objectif A.
- [ ] Ouvrir l'accueil, un article, la demande de projet et l'admin avec la console du navigateur (F12) ouverte : aucune alerte « Content Security Policy ». Après une semaine sans alerte, mettre `CSP_ENFORCE=true`.
- [ ] Activer **Dependabot alerts** sur GitHub (Settings → Code security) ; les pull requests de mises à jour arriveront chaque lundi.
- [ ] Renouveler `public/.well-known/security.txt` avant le **20 novembre 2027** (champ `Expires`).

## 8. Décisions en attente
- [ ] Valider la migration 2FA de l'admin.
- [ ] Retirer ou garder la route `/contact` (ancien formulaire).
- [ ] Suivi d'erreurs : Sentry (gratuit jusqu'à 5 000 erreurs/mois) ou Flare (payant, fait pour Laravel), ou rien.
