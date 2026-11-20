# SEO — hounkpatinprimous.com

Site vitrine de services + blog · Laravel 12 · Cotonou, Bénin · public : commerçants, artisans, indépendants · français.
Dernière mise à jour : 20 novembre 2026.

> Hypothèses retenues (à corriger si besoin) : domaine `https://hounkpatinprimous.com`, activité sans boutique physique
> (zone desservie : Cotonou et tout le Bénin), adresse précise non communiquée.
> Les mots-clés ci-dessous sont des **hypothèses de recherche** : aucun volume n'a été mesuré. Ils seront confirmés
> (ou remplacés) par les vraies requêtes visibles dans Search Console après 4 à 8 semaines.

---

## 1. Audit rapide

| Problème constaté | Gravité | Correction | État |
|---|---|---|---|
| Pas de `sitemap.xml` | Élevée | Sitemap dynamique (pages + articles publiés, `lastmod`) | ✅ fait |
| `robots.txt` vide, sans sitemap | Élevée | `robots.txt` dynamique : bloque `/admin`, `/livewire`, annonce le sitemap | ✅ fait |
| Aucune balise `canonical` | Élevée | Canonical sur chaque page, sans paramètres de suivi (utm, fbclid) | ✅ fait |
| Titres sans mot-clé ni ville | Élevée | Titres 50-60 caractères avec « Cotonou » / « Bénin » | ✅ fait |
| Descriptions non optimisées | Moyenne | Descriptions 140-155 caractères, uniques | ✅ fait |
| Aucune donnée structurée | Élevée | JSON-LD : ProfessionalService, Person, WebSite, FAQPage, BreadcrumbList, BlogPosting | ✅ fait |
| Pas d'Open Graph / Twitter | Moyenne | Balises de partage + image 1200 × 630 | ✅ fait |
| Pas de compression ni de cache navigateur | Élevée | `mod_deflate` + `mod_expires` + cache immuable des fichiers du build | ✅ fait |
| HTTP / www non redirigés | Élevée | Redirection 301 vers `https://hounkpatinprimous.com` (dans `.htaccess`) | ✅ fait |
| Logo de 97 Ko affiché à 140 px | Moyenne | Logos allégés de 26 Ko (`logo-400.png`) | ✅ fait |
| 58 Ko de JavaScript inutile (axios) | Moyenne | Retiré : 0,8 Ko de JS sur le site public | ✅ fait |
| Image principale chargée sans priorité | Moyenne | `fetchpriority="high"` sur le portrait du hero | ✅ fait |
| Ville peu présente dans le contenu | Moyenne | Cotonou dans le badge du hero, l'intro Services, le pied de page | ✅ fait |
| Un titre affichait « &nbsp; » en clair (page Avis) | Faible | Corrigé | ✅ fait |
| Site pas encore en ligne / HTTPS | Bloquante | Mise en ligne Hostinger + SSL (voir P0) | ⏳ à faire |
| Pas de fiche Google Business Profile | Élevée | Création (section 6) | ⏳ à faire |
| Aucun avis client publié | Élevée | Collecte d'avis réels (page Avis + Google) | ⏳ à faire |
| Infos NAP incomplètes (adresse, réseaux) | Moyenne | Renseigner `SITE_ADRESSE` et `SITE_FACEBOOK`… dans `.env` | ⏳ à faire |

Déjà bon : un seul H1 par page, URLs propres en français, site responsive (mobile d'abord), polices auto-hébergées,
images avec `width`/`height` et `loading="lazy"` sous la ligne de flottaison, aucune animation qui décale la page.

---

## 2. Plan d'action priorisé

**P0 — aujourd'hui**
1. Mettre le site en ligne sur Hostinger, activer le certificat SSL (hPanel → Sécurité → SSL).
2. `.env` de production : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://hounkpatinprimous.com`.
3. Sur le serveur : `composer install --no-dev -o`, `npm run build`, `php artisan migrate --force`, `php artisan optimize`.
4. Vérifier `https://hounkpatinprimous.com/robots.txt` et `/sitemap.xml`.
5. Déclarer le site dans Google Search Console et envoyer le sitemap (section 3).

**P1 — cette semaine**
1. Créer la fiche Google Business Profile (section 6), avec les mêmes nom, ville et téléphone que le site.
2. Renseigner dans `.env` : `SITE_ADRESSE`, `SITE_FACEBOOK`, `SITE_INSTAGRAM`, `SITE_LINKEDIN`, `SITE_GOOGLE_BUSINESS` (alimentent les données structurées).
3. Envoyer le message de demande d'avis à 5 anciens clients (page Avis + fiche Google).
4. Tester les pages dans PageSpeed Insights (mobile) et le Test des résultats enrichis de Google.

**P2 — ce mois**
1. Publier 4 nouveaux articles (calendrier section 4).
2. Obtenir 3 premiers liens : fiche Google, page Facebook, lien « Site réalisé par » sur les sites des clients.
3. Enrichir les 6 projets dans l'admin (contexte, résultat, lien vers le site en ligne).
4. Premier point Search Console : requêtes réelles, pages indexées, erreurs.

---

## 3. Bases techniques

### HTTPS, responsive, mobile-first
- **HTTPS** : `.htaccess` redirige en 301 `http://` et `www.` vers `https://hounkpatinprimous.com` (règle limitée à ce domaine, sans effet en local). `AppServiceProvider` force `https` dans toutes les URL générées en production.
- **Responsive** : vérifié à 375, 768 et 1280 px, sans défilement horizontal. Google indexe la version mobile : elle contient tout le contenu.
- **Indexation** : `<meta name="robots" content="index, follow, max-image-preview:large">` sur les pages publiques.

### sitemap.xml et robots.txt
Générés par `app/Http/Controllers/SeoController.php` (routes `/sitemap.xml` et `/robots.txt`), toujours sur le domaine courant.
Chaque article publié y entre automatiquement ; un brouillon n'y apparaît jamais.

Exemple produit en ligne :

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://hounkpatinprimous.com</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc>https://hounkpatinprimous.com/services</loc><changefreq>monthly</changefreq><priority>0.9</priority></url>
  <url><loc>https://hounkpatinprimous.com/demande-de-projet</loc><changefreq>yearly</changefreq><priority>0.9</priority></url>
  <url><loc>https://hounkpatinprimous.com/projets</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc>https://hounkpatinprimous.com/blog</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://hounkpatinprimous.com/blog/les-7-etapes-qui-transforment-un-visiteur-en-client</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <!-- … les 6 articles, parcours, avis, pages légales -->
</urlset>
```

```text
User-agent: *
Allow: /
Disallow: /admin
Disallow: /livewire
Disallow: /suggestion
Disallow: /contact
Disallow: /*?page=

Sitemap: https://hounkpatinprimous.com/sitemap.xml
```

### Google Search Console (pas à pas)
1. Aller sur search.google.com/search-console, se connecter avec le compte Google du site.
2. « Ajouter une propriété » → **Domaine** → saisir `hounkpatinprimous.com`.
3. Google donne un enregistrement **TXT** : hPanel Hostinger → Domaines → DNS → ajouter un enregistrement TXT (nom `@`, valeur fournie). Attendre quelques minutes puis « Valider ».
4. Menu **Sitemaps** → saisir `sitemap.xml` → Envoyer. Statut attendu : « Opération effectuée ».
5. **Inspection de l'URL** → coller l'URL de l'accueil → « Demander une indexation ». Refaire pour `/services` et `/blog`.
6. Faire de même sur Bing Webmaster Tools (import direct depuis Search Console en un clic).

### Structure des URLs
Déjà propres, courtes, en français, sans paramètres :

| Page | URL |
|---|---|
| Accueil | `/` |
| Services | `/services` |
| Projets | `/projets` |
| Parcours | `/parcours` |
| Avis | `/avis` |
| Blog | `/blog` · filtre `/blog?categorie=strategie` |
| Article | `/blog/les-7-etapes-qui-transforment-un-visiteur-en-client` |
| Demande | `/demande-de-projet` |

**Redirections 301** : aucune ancienne URL à rediriger (nouveau site). Déjà en place : `http://` → `https://`, `www.` → sans `www`, barre finale `/services/` → `/services`.
Si l'ancien site monportfolio a été en ligne sur ce domaine, ajouter au besoin (`routes/web.php`) :
`Route::redirect('/ancienne-page', '/services', 301);`

---

## 4. Vitesse — Core Web Vitals

| Optimisation | Où | État |
|---|---|---|
| Compression gzip HTML/CSS/JS/SVG/XML | `public/.htaccess` (`mod_deflate`) | ✅ |
| Cache navigateur 30 jours (images), 1 an immuable (build) | `public/.htaccess` (`mod_expires`, `mod_headers`) | ✅ |
| CSS et JS minifiés | `npm run build` (Vite) | ✅ |
| JavaScript réduit à 0,8 Ko | `resources/js/app.js` (axios retiré) | ✅ |
| `loading="lazy"` sous la ligne de flottaison | carrousel, captures, logos du pied de page | ✅ |
| `width` / `height` sur toutes les images | toutes les vues | ✅ |
| Image LCP prioritaire | portrait du hero : `fetchpriority="high"` | ✅ |
| Polices auto-hébergées, `font-display: swap` | Geist (fontsource) | ✅ |
| Logo allégé (97 → 26 Ko) | `images/brand/logo-400.png` | ✅ |
| Captures des projets en WebP (−30 à −50 %) | à faire au prochain ajout de projet | ⏳ |

Code ajouté (extrait de `public/.htaccess`) :

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/css text/xml application/xml application/javascript application/json image/svg+xml
</IfModule>
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 30 days"
    ExpiresByType image/webp "access plus 30 days"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
</IfModule>
```

**Pour passer au vert sur PageSpeed Insights**
- **LCP** (< 2,5 s) : sur mobile, l'élément le plus grand est le titre H1 (texte, rapide) ; sur ordinateur, le portrait (51 Ko, prioritaire). Garder les captures sous 150 Ko ; activer le cache LiteSpeed d'Hostinger si disponible.
- **INP** (< 200 ms) : le site n'a presque pas de JavaScript (Alpine via Livewire seulement). Rien à faire.
- **CLS** (< 0,1) : toutes les images ont leurs dimensions ; les animations n'utilisent que l'opacité et la transformation. Ne jamais insérer d'image sans `width`/`height`.

---

## 5. Balises par page

| Page | `<title>` (car.) | Meta description (car.) | H1 | Mot-clé principal |
|---|---|---|---|---|
| Accueil | Création de site internet à Cotonou \| Hounkpatin Primous (56) | Création de sites internet à Cotonou pour commerçants, artisans et indépendants : un site clair qui fait écrire vos clients. Premier échange gratuit. (149) | Expliquez votre activité une seule fois. | création site internet Cotonou |
| Services | Services web à Cotonou : site, copywriting, Facebook Ads (56) | Site vitrine, landing page, copywriting, Facebook Ads et contenus à Cotonou : des outils pensés pour transformer vos visiteurs en clients au Bénin. (147) | Un site pensé pour votre activité, pas un modèle. | site vitrine Cotonou |
| Projets | Réalisations web au Bénin : sites et plateformes \| Primous (58) | Découvrez les sites conçus et développés par Hounkpatin Primous au Bénin : plateformes, pages de présentation et outils en ligne clairs et efficaces. (149) | Les projets que j'ai conçus et développés. | développeur web Bénin |
| Parcours | Développeur web et closeur à Cotonou \| Hounkpatin Primous (57) | Développeur de formation et closeur certifié MarketingLab, Hounkpatin Primous crée à Cotonou des sites qui guident chaque visiteur jusqu'à l'action. (148) | Du code à la vente : mon parcours. | développeur web Cotonou |
| Blog | Blog : conseils pour un site internet qui vend \| Primous (56) | Conseils concrets pour créer un site internet qui vend au Bénin : parcours client, réassurance, formulaires et exemples par activité. Articles courts. (150) | Conseils pour un site qui se comprend tout seul. | site internet qui vend |
| Demande | Demande de devis site internet à Cotonou \| Primous (50) | Décrivez votre projet de site internet en 5 questions et recevez une proposition adaptée à votre activité. Création de sites à Cotonou, échange gratuit. (152) | Parlez-moi de votre projet | devis site internet Cotonou |
| Avis | Avis clients : création de site à Cotonou \| Primous (51) | (149 car., unique) | Ce qu'en disent mes clients. | avis création site Cotonou |

**Structure H2/H3 (accueil)** : H1 → H2 « Ce que votre site fait pour vous » (H3 × 3) → H2 « Voyez par vous-même » (H3 = projets) → H2 « Vous méritez mieux… » → H2 « Soyez l'entrepreneur… » (H3) → H2 « Trois étapes simples » (H3 × 3) → H2 « Je fais pour vous… » → H2 « Ce que vous vous demandez sûrement » → H2 CTA. Ordre logique, aucun niveau sauté.

**Attributs alt** : portrait « Hounkpatin Primous, créateur de sites internet à Cotonou » ; captures « Aperçu du projet Evenzia » (etc.) ; logo « Hounkpatin Primous ». Images décoratives : `alt=""` / `aria-hidden`.

**Canonical** : `<link rel="canonical" href="https://hounkpatinprimous.com/services">` sur chaque page (généré dans `resources/views/layouts/app.blade.php`). Seuls `?categorie=` et `?page=` (au-delà de 1) sont conservés.

**Partage** : `og:title`, `og:description`, `og:url`, `og:image` (`images/brand/og-default.jpg`, 1200 × 630), `og:type` (`article` sur le blog), `twitter:card=summary_large_image`.

---

## 6. Contenu et mots-clés

### Mot-clé principal par page
Voir la dernière colonne du tableau ci-dessus. Mots-clés secondaires à glisser naturellement : « site web Bénin », « landing page Cotonou », « copywriting Bénin », « publicité Facebook Cotonou », « site vitrine pour commerçant », « site internet pas cher Cotonou » (à n'utiliser que si l'offre le justifie).

### 10 idées de contenus (originaux, utiles, orientés Bénin)
1. Combien coûte un site internet à Cotonou ? Ce qui fait varier le prix *(guide — répond à la 1re question des prospects)*
2. Site internet ou page Facebook : de quoi votre activité a-t-elle vraiment besoin ?
3. Vendre sur WhatsApp avec un site : le parcours qui évite les 50 messages par jour
4. Landing page : à quoi ça sert et quand en avoir une pour sa publicité Facebook
5. 10 informations que chaque commerçant doit afficher sur son site (prix, livraison, horaires…)
6. Comment écrire le titre de sa page d'accueil en 10 minutes *(copywriting appliqué)*
7. Google Business Profile au Bénin : créer et optimiser sa fiche, pas à pas
8. Faut-il afficher ses prix sur son site ? Avantages, risques, alternatives
9. Étude de cas : avant / après la refonte d'un site (avec un vrai client, avec son accord)
10. FAQ : nom de domaine, hébergement, mise à jour… tout ce qu'on se demande avant de créer son site

### Maillage interne
| Depuis | Vers | Texte d'ancre |
|---|---|---|
| Chaque article | `/demande-de-projet` | Démarrer mon projet *(en place)* |
| Article « combien coûte un site » | `/services` | nos offres de création de site |
| Article « landing page » | `/services` | landing page pour vos publicités |
| Articles « parcours client » | `/projets` | des exemples de sites réalisés |
| Accueil (carrousel) | `/projets` | Voir tous les projets *(en place)* |
| Services (cartes) | articles liés | En savoir plus sur … |
| Parcours | `/projets` | Voir tous les projets *(en place)* |
| Projets | `/avis` | ce qu'en disent mes clients |
| Article | articles de la même catégorie | « À lire aussi » *(en place)* |

### Calendrier réaliste
**1 article toutes les deux semaines** pendant 3 mois (6 articles), puis **1 par mois**. Chaque article : 600 à 1 000 mots, 1 mot-clé, 2 liens internes, 1 appel à l'action. Ordre conseillé : idées 1, 5, 2, 4, 7, 3, puis le reste.

---

## 7. Données structurées (JSON-LD)

Toutes générées automatiquement, valides (contrôlées par `tests/Feature/SeoTest.php`) :

- **Toutes les pages** (`resources/views/seo/schema-site.blade.php`) : `ProfessionalService` (sous-type de LocalBusiness) + `Person` (fondateur) + `WebSite`.
- **Accueil et Services** : `FAQPage` (mêmes questions que la FAQ affichée).
- **Pages intérieures** : `BreadcrumbList`.
- **Articles** : `BlogPosting` (auteur, dates, catégorie, lectures, commentaires) + `BreadcrumbList`.

Extrait produit :

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "ProfessionalService",
      "@id": "https://hounkpatinprimous.com/#entreprise",
      "name": "Hounkpatin Primous",
      "slogan": "Votre référence digitale préférée",
      "url": "https://hounkpatinprimous.com",
      "logo": "https://hounkpatinprimous.com/images/brand/logo.png",
      "email": "contact@hounkpatinprimous.com",
      "telephone": "+2290194384161",
      "address": { "@type": "PostalAddress", "addressLocality": "Cotonou", "addressCountry": "BJ" },
      "areaServed": [{ "@type": "City", "name": "Cotonou" }, { "@type": "Country", "name": "Bénin" }],
      "knowsAbout": ["Création de site internet", "Landing page", "Copywriting", "Facebook Ads", "Création de contenu"],
      "founder": { "@id": "https://hounkpatinprimous.com/#fondateur" }
    },
    { "@type": "Person", "@id": "https://hounkpatinprimous.com/#fondateur", "name": "Hounkpatin Primous", "jobTitle": "Créateur de sites internet" },
    { "@type": "WebSite", "@id": "https://hounkpatinprimous.com/#site", "url": "https://hounkpatinprimous.com", "name": "Hounkpatin Primous", "inLanguage": "fr" }
  ]
}
```

Non utilisés (hors sujet) : `Product`, `RealEstateListing`. Pas d'`AggregateRating` tant qu'il n'y a pas d'avis réels publiés.
Vérification : search.google.com/test/rich-results et validator.schema.org.

---

## 8. SEO local

### Google Business Profile, pas à pas
1. business.google.com → « Gérer maintenant » avec le compte Google du site.
2. Nom : **Hounkpatin Primous** (exactement, sans mots-clés ajoutés).
3. Catégorie principale : **Concepteur de sites Web** ; secondaires : *Service de marketing Internet*, *Agence de publicité*.
4. Pas de local qui reçoit des clients → choisir « Je livre des produits et services à mes clients » et **masquer l'adresse** ; zone desservie : Cotonou, Abomey-Calavi, Porto-Novo, Bénin.
5. Téléphone : **+229 01 94 38 41 61** (même numéro que sur le site) ; site web : `https://hounkpatinprimous.com`.
6. Valider la fiche (vidéo, SMS ou appel selon ce que Google propose).
7. Compléter : description (750 caractères, avec « création de sites internet à Cotonou »), services (les 5 du site, avec description), horaires, logo, photo de profil (portrait), 5 à 10 photos de projets.
8. Chaque semaine : 1 publication (lien vers un article ou un projet), réponse à chaque avis.
9. Demander un avis Google à chaque client satisfait (lien court fourni par la fiche).

### Ville et pays dans le site
En place : titres (`à Cotonou`, `au Bénin`), descriptions, badge du hero « … à Cotonou », introduction de la page Services « À Cotonou comme à distance… », pied de page « Cotonou, Bénin », données structurées. Garder le H1 de l'accueil orienté client (choix de copywriting validé) : la ville est portée par le titre, le badge et le contenu.

### Règle NAP
**N**om, **A**dresse, **T**éléphone strictement identiques partout :

```
Hounkpatin Primous
Cotonou, Bénin
+229 01 94 38 41 61
contact@hounkpatinprimous.com
https://hounkpatinprimous.com
```

À recopier à l'identique : site (pied de page, mentions légales, données structurées — déjà fait), fiche Google, page Facebook, Instagram, LinkedIn, WhatsApp Business, annuaires. Ne pas alterner « Primous », « HP Digital », « Hounkpatin P. ». Vérifier aussi le numéro WhatsApp : `SITE_WHATSAPP` et `SITE_PHONE` doivent être cohérents.

---

## 9. Popularité et autorité

### 10 sources de liens fiables
1. **Google Business Profile** (lien vers le site, signal local majeur).
2. **Page Facebook professionnelle** + **Instagram** + **LinkedIn** (profil et page) — liens dans la bio.
3. **Sites des clients** : mention « Site réalisé par Hounkpatin Primous » en pied de page (avec leur accord).
4. **MarketingLab Institut** : page alumni / témoignage d'ancien élève.
5. **Gaza Formation** : page anciens étudiants, si elle existe.
6. **CCI Bénin** et **APIEx** : annuaires d'entreprises (à vérifier selon ton statut).
7. **Sèmè City** et événements tech locaux (meetups, hackathons) : profils de participants, articles.
8. **Presse en ligne béninoise** (ex. Banouto, La Nouvelle Tribune, 24 Haubenin) : tribune ou interview sur la digitalisation des commerces.
9. **Plateformes freelance** (Malt, Upwork, Fiverr, ComeUp) : profil avec lien vers le portfolio.
10. **Bing Places**, **Apple Business Connect**, **GitHub** (profil public avec lien).

À éviter : achat de liens, annuaires étrangers sans rapport, échanges de liens en masse.

### Réseaux sociaux qui renvoient vers le site
Règle : chaque post apporte de la valeur sur la plateforme **et** propose un lien vers une page précise (article, projet, demande).

1. **Carrousel « Avant / après »** d'une page d'accueil (titre flou → titre clair) + lien vers l'article « Pourquoi un beau site ne suffit pas à vendre ».
2. **Vidéo 30 s** : « Les 3 questions que vos clients vous posent 50 fois par jour » + lien vers l'accueil.
3. **Post liste** « 7 étapes qui transforment un visiteur en client » (1 slide par étape) + lien vers l'article.
4. **Coulisses d'un projet** (capture Evenzia ou Petits Pas, ce qui a été fait) + lien vers `/projets`.
5. **Sondage** « Vos clients vous demandent surtout : prix / livraison / disponibilité ? » puis post de réponse avec lien vers l'article « Ce qui rassure un visiteur ».

---

## 10. KPI à suivre dans Search Console

| Indicateur | Où | Objectif à 3 mois | Fréquence |
|---|---|---|---|
| Pages indexées | Indexation → Pages | Toutes les pages du sitemap (≈ 15), 0 erreur | Mensuelle |
| Impressions | Performances | Hausse continue, mois après mois | Hebdomadaire |
| Clics | Performances | Premiers clics sur « création site internet Cotonou » | Hebdomadaire |
| CTR moyen | Performances | > 3 % ; sinon retravailler le titre / la description de la page | Mensuelle |
| Position moyenne | Performances → Requêtes | Top 10 sur 2-3 requêtes locales | Mensuelle |
| Core Web Vitals | Expérience → Signaux Web essentiels | 100 % des URL « Bonnes » | Mensuelle |
| Résultats enrichis | Améliorations (FAQ, fil d'Ariane) | 0 erreur | Mensuelle |

Hors Search Console : demandes de projet reçues (admin → Demandes), appels / messages WhatsApp mentionnant Google, avis Google.

---

## Fichiers modifiés pour le SEO
- `app/Http/Controllers/SeoController.php` — sitemap.xml et robots.txt
- `resources/views/seo/sitemap.blade.php`, `resources/views/seo/schema-site.blade.php`
- `resources/views/layouts/app.blade.php` — canonical, robots, Open Graph, Twitter, JSON-LD
- `resources/views/components/layout/page-hero.blade.php` — BreadcrumbList
- `resources/views/components/sections/home-faq.blade.php` — FAQPage
- `resources/views/pages/article.blade.php` — BlogPosting
- Titres et descriptions : toutes les pages
- `public/.htaccess` — HTTPS, compression, cache
- `app/Providers/AppServiceProvider.php` — HTTPS forcé en production
- `resources/js/app.js` — axios retiré
- `public/images/brand/og-default.jpg`, `logo-400.png`, `logo-light-400.png`
- `config/site.php` — profils sociaux (`social`)
- `tests/Feature/SeoTest.php` — 5 tests SEO
