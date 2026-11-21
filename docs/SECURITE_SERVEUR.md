# Sécurité du serveur — configurations à appliquer à la main

Rien de ce fichier n'a été exécuté. Deux cas selon ton offre Hostinger :
- **Hébergement mutualisé / Business (hPanel)** : sections 1, 2, 3 et 8. Le reste est géré par Hostinger.
- **VPS** : toutes les sections.

Remplace `hounkpatinprimous.com`, `deploy` (utilisateur système) et `/var/www/myportfolio` par tes valeurs.

---

## 1. Racine du site = `public/` (le point le plus important)

**Mutualisé (hPanel)** : envoie le projet **à côté** de `public_html`, jamais dedans :

```
/home/uXXXX/domains/hounkpatinprimous.com/
├── myportfolio/          ← tout le projet (app, vendor, .env…)
└── public_html  →  lien vers myportfolio/public
```

En SSH (hPanel → Avancé → Accès SSH) :

```bash
cd ~/domains/hounkpatinprimous.com
mv public_html public_html.ancien
ln -s ~/domains/hounkpatinprimous.com/myportfolio/public public_html
```

Si le lien symbolique est impossible, le `.htaccess` racine du projet renvoie tout vers `public/` et refuse les fichiers cachés : c'est un filet, pas la solution.

**Vérification** (doit renvoyer 403 ou 404, jamais 200) :

```bash
curl -I https://hounkpatinprimous.com/.env
curl -I https://hounkpatinprimous.com/.git/config
curl -I https://hounkpatinprimous.com/vendor/autoload.php
curl -I https://hounkpatinprimous.com/storage/logs/laravel.log
```

## 2. Droits des fichiers

```bash
cd ~/domains/hounkpatinprimous.com/myportfolio
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 600 .env
chmod -R 775 storage bootstrap/cache public/images/uploads
```

## 3. Tâche planifiée (sauvegardes de nuit)

hPanel → Avancé → Tâches Cron → toutes les minutes :

```
* * * * * cd /home/uXXXX/domains/hounkpatinprimous.com/myportfolio && php artisan schedule:run >> /dev/null 2>&1
```

Sur VPS : `crontab -u deploy -e` et la même ligne avec `/var/www/myportfolio`.

---

## 4. Nginx (VPS)

`/etc/nginx/sites-available/hounkpatinprimous.com` :

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name hounkpatinprimous.com www.hounkpatinprimous.com;
    return 301 https://hounkpatinprimous.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name www.hounkpatinprimous.com;
    ssl_certificate     /etc/letsencrypt/live/hounkpatinprimous.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/hounkpatinprimous.com/privkey.pem;
    return 301 https://hounkpatinprimous.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name hounkpatinprimous.com;
    root /var/www/myportfolio/public;
    index index.php;
    charset utf-8;
    client_max_body_size 8M;
    server_tokens off;

    ssl_certificate     /etc/letsencrypt/live/hounkpatinprimous.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/hounkpatinprimous.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_stapling on;
    ssl_stapling_verify on;

    # Fichiers cachés (.env, .git…) : envoyés à Laravel, qui renvoie 404, journalise et bloque l'IP
    # en cas de scan (DetecteurDeScans). /.well-known reste servi (security.txt, Let's Encrypt).
    location ~ /\.(?!well-known) { rewrite ^ /index.php last; }

    # Aucun PHP exécuté dans le dossier des téléversements
    location ^~ /images/uploads/ {
        location ~ \.php$ { deny all; }
        add_header X-Content-Type-Options nosniff;
        add_header X-Frame-Options DENY;
        expires 30d;
    }

    location ~* \.(?:css|js|woff2)$ { expires 1y; add_header Cache-Control "public, immutable"; }
    location ~* \.(?:jpe?g|png|webp|svg|ico)$ { expires 30d; }

    gzip on;
    gzip_types text/plain text/css application/javascript application/json image/svg+xml application/xml;

    location / { try_files $uri $uri/ /index.php?$query_string; }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { try_files $uri /index.php?$query_string; access_log off; }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        fastcgi_hide_header X-Powered-By;
        internal;
    }

    # Tout autre fichier .php : refusé
    location ~ \.php$ { return 404; }

    # Limite supplémentaire sur la connexion admin (en plus de Laravel)
    location = /admin/login { limit_req zone=login burst=5 nodelay; try_files $uri /index.php?$query_string; }
}
```

Dans `/etc/nginx/nginx.conf`, bloc `http { }` :

```nginx
limit_req_zone $binary_remote_addr zone=login:10m rate=10r/m;
server_tokens off;
```

```bash
sudo ln -s /etc/nginx/sites-available/hounkpatinprimous.com /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

Les en-têtes de sécurité (HSTS, CSP…) sont envoyés par Laravel (`SecurityHeaders`) : ne pas les dupliquer ici.

## 5. Apache (VPS sans Nginx)

`/etc/apache2/sites-available/hounkpatinprimous.com.conf` :

```apache
<VirtualHost *:80>
    ServerName hounkpatinprimous.com
    ServerAlias www.hounkpatinprimous.com
    Redirect permanent / https://hounkpatinprimous.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName hounkpatinprimous.com
    DocumentRoot /var/www/myportfolio/public

    <Directory /var/www/myportfolio/public>
        AllowOverride All
        Require all granted
        Options -Indexes -MultiViews
    </Directory>

    # Le reste du projet n'est jamais servi
    <Directory /var/www/myportfolio>
        Require all denied
    </Directory>

    SSLEngine on
    SSLCertificateFile    /etc/letsencrypt/live/hounkpatinprimous.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/hounkpatinprimous.com/privkey.pem
    SSLProtocol -all +TLSv1.2 +TLSv1.3
</VirtualHost>
```

`/etc/apache2/conf-available/security.conf` : `ServerTokens Prod`, `ServerSignature Off`, `TraceEnable Off`.

```bash
sudo a2enmod rewrite headers ssl expires deflate
sudo a2ensite hounkpatinprimous.com && sudo apachectl configtest && sudo systemctl reload apache2
```

## 6. SSL (Let's Encrypt)

Mutualisé : hPanel → Sécurité → SSL → installer le certificat gratuit, activer « Forcer HTTPS ».

VPS :

```bash
sudo apt install certbot python3-certbot-nginx     # ou python3-certbot-apache
sudo certbot --nginx -d hounkpatinprimous.com -d www.hounkpatinprimous.com
sudo certbot renew --dry-run                        # renouvellement automatique testé
```

Contrôle : https://www.ssllabs.com/ssltest/ → objectif A ou A+.

## 7. SSH, pare-feu, Fail2ban (VPS)

**SSH** — `/etc/ssh/sshd_config.d/90-securite.conf` (après avoir copié ta clé avec `ssh-copy-id deploy@IP` et vérifié qu'elle fonctionne) :

```
PermitRootLogin no
PasswordAuthentication no
KbdInteractiveAuthentication no
PubkeyAuthentication yes
MaxAuthTries 3
LoginGraceTime 30
AllowUsers deploy
X11Forwarding no
```

```bash
sudo sshd -t && sudo systemctl reload ssh
```

Garde une session SSH ouverte pendant le test : si la nouvelle connexion échoue, tu peux encore corriger.

**Pare-feu (UFW)** :

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'      # ou 'Apache Full'
sudo ufw enable
sudo ufw status verbose
```

MySQL (3306) n'est jamais ouvert vers l'extérieur : dans `/etc/mysql/mysql.conf.d/mysqld.cnf`, `bind-address = 127.0.0.1`.

**Fail2ban** — `/etc/fail2ban/jail.local` :

```ini
[DEFAULT]
bantime  = 1h
findtime = 10m
maxretry = 5
banaction = ufw

[sshd]
enabled = true

[nginx-http-auth]
enabled = true

[nginx-limit-req]
enabled  = true
logpath  = /var/log/nginx/error.log
maxretry = 10
```

```bash
sudo apt install fail2ban
sudo systemctl enable --now fail2ban
sudo fail2ban-client status sshd
```

**Mises à jour automatiques** :

```bash
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

**Utilisateur MySQL dédié** (jamais root pour le site) :

```sql
CREATE USER 'myportfolio'@'localhost' IDENTIFIED BY 'mot-de-passe-long-et-unique';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP, REFERENCES, LOCK TABLES ON myportfolio.* TO 'myportfolio'@'localhost';
FLUSH PRIVILEGES;
```

## 8. Supervisor (file d'attente, VPS — facultatif)

Aujourd'hui les e-mails partent immédiatement (pas de file d'attente). Si tu passes plus tard les notifications en file (`ShouldQueue`), ajoute `/etc/supervisor/conf.d/myportfolio-worker.conf` :

```ini
[program:myportfolio-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/myportfolio/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
user=deploy
numprocs=1
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
stopwaitsecs=3600
redirect_stderr=true
stdout_logfile=/var/www/myportfolio/storage/logs/worker.log
```

```bash
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start myportfolio-worker:*
```

Sur mutualisé : une tâche cron `php artisan queue:work --stop-when-empty` toutes les minutes fait le même travail.

## 9. PHP (`php.ini` de production)

```ini
expose_php = Off
display_errors = Off
log_errors = On
allow_url_include = Off
session.cookie_httponly = 1
session.cookie_secure = 1
upload_max_filesize = 8M
post_max_size = 8M
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,pcntl_exec
```

⚠️ `disable_functions` : les sauvegardes appellent `mysqldump` via `proc_open`. Si tu le désactives, lance les sauvegardes depuis le cron système (`php -d disable_functions= artisan backup:run`) ou garde `proc_open` autorisé.
