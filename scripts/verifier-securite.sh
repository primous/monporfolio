#!/usr/bin/env bash
# Vérification de sécurité du site en ligne (anti-Google Dorking).
# Usage : bash scripts/verifier-securite.sh https://hounkpatinprimous.com [adresse-admin]
#
# ⚠️ Le site bloque 24 h une IP qui demande plus de 10 chemins sensibles en 5 minutes.
#    Ce script en demande ~25 : lance-le depuis une connexion que tu peux te permettre de voir
#    bloquée (données mobiles, par exemple), ou débloque-toi ensuite sur le serveur :
#    php artisan tinker --execute='Cache::forget("scan:bloque:TON.IP"); Cache::forget("scan:compte:TON.IP");'

set -u
SITE="${1:-https://hounkpatinprimous.com}"
SITE="${SITE%/}"
ADMIN="${2:-}"
OK=0; KO=0

vert() { printf '\033[32m✔ %s\033[0m\n' "$1"; OK=$((OK+1)); }
rouge() { printf '\033[31m✘ %s\033[0m\n' "$1"; KO=$((KO+1)); }

code() { curl -s -o /dev/null -w '%{http_code}' --max-time 15 "$SITE$1"; }
entetes() { curl -sI --max-time 15 "$SITE$1"; }

echo "== Fichiers sensibles (attendu : 403 ou 404) =="
for chemin in /.env /.env.backup /.git/HEAD /.git/config /storage/logs/laravel.log /vendor/autoload.php \
  /config/app.php /database/database.sqlite /backup.sql /composer.json /id_rsa /phpinfo.php \
  /wp-admin/ /phpmyadmin /actuator/env /server-status /telescope /up; do
  c=$(code "$chemin")
  if [ "$c" = "403" ] || [ "$c" = "404" ]; then vert "$chemin → $c"; else rouge "$chemin → $c"; fi
done

echo "== En-têtes de sécurité sur la page d'accueil =="
H=$(entetes /)
for e in "strict-transport-security" "content-security-policy:" "x-frame-options: deny" \
  "x-content-type-options: nosniff" "referrer-policy: strict-origin-when-cross-origin" "permissions-policy"; do
  if echo "$H" | grep -qi "^$e"; then vert "$e"; else rouge "$e absent"; fi
done
if echo "$H" | grep -qi "^x-powered-by"; then rouge "X-Powered-By présent"; else vert "X-Powered-By absent"; fi

echo "== HTTPS et domaine unique =="
[ "$(curl -s -o /dev/null -w '%{http_code}' "http://${SITE#https://}/")" = "301" ] && vert "http → 301" || rouge "http ne redirige pas en 301"
[ "$(curl -s -o /dev/null -w '%{redirect_url}' "https://www.${SITE#https://}/")" = "$SITE/" ] && vert "www → domaine sans www" || rouge "www ne redirige pas vers $SITE/"

echo "== Discrétion =="
curl -s "$SITE/" "$SITE/mentions-legales" "$SITE/politique-confidentialite" | grep -qiE 'mailto:[^"'\'' ]+@|[a-z0-9._-]+@hounkpatinprimous\.com' \
  && rouge "e-mail en clair dans le HTML" || vert "aucun e-mail en clair"
curl -s "$SITE/page-qui-nexiste-pas" | grep -qi "laravel\|Not Found" && rouge "404 trahit Laravel" || vert "404 personnalisée"
R=$(curl -s "$SITE/robots.txt")
echo "$R" | grep -q "Disallow: /.env" && vert "robots.txt : leurres présents" || rouge "robots.txt incomplet"
if [ -n "$ADMIN" ]; then
  echo "$R" | grep -q "$ADMIN" && rouge "robots.txt révèle l'adresse de l'admin" || vert "robots.txt ne révèle pas l'admin"
  entetes "/$ADMIN/login" | grep -qi "^x-robots-tag: noindex" && vert "admin : X-Robots-Tag noindex" || rouge "admin indexable"
  [ "$(code /admin/login)" = "404" ] && vert "/admin/login → 404 (leurre)" || rouge "/admin/login répond encore"
fi
[ "$(code /.well-known/security.txt)" = "200" ] && vert "security.txt présent" || rouge "security.txt absent"

echo
echo "Résultat : $OK conformes, $KO à corriger."
[ "$KO" -eq 0 ]
