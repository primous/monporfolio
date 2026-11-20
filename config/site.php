<?php

// Contenus partagés par la navbar, le footer et le formulaire de demande.
// Les valeurs personnelles vont dans .env (SITE_*), pas dans ce fichier.

return [
    'name' => env('APP_NAME', 'Primous'),

    'owner' => 'Hounkpatin Primous',

    'phone' => env('SITE_PHONE'),

    'email' => env('SITE_EMAIL'),

    'whatsapp' => env('SITE_WHATSAPP', ''),

    'whatsapp_message' => 'Bonjour, je vous contacte depuis votre site.',

    'notify_email' => env('SITE_NOTIFY_EMAIL', ''),

    'admin_email' => env('SITE_ADMIN_EMAIL', ''),

    // Informations légales (pages Mentions légales / Politique de confidentialité).
    // Une ligne vide n'est pas affichée : renseignez-les dans .env dès que vous les avez.
    'legal' => [
        'statut' => env('SITE_STATUT'),          // ex. Entreprise individuelle
        'ifu' => env('SITE_IFU'),                // numéro IFU (Bénin)
        'rccm' => env('SITE_RCCM'),              // numéro RCCM, si immatriculé
        'adresse' => env('SITE_ADRESSE'),        // adresse postale, ex. Cotonou, Bénin
        'mise_a_jour' => '20 novembre 2026',     // date de dernière mise à jour des deux pages
        // Hébergeur du site
        'hebergeur' => [
            'nom' => 'Hostinger International Ltd.',
            'adresse' => '61 Lordou Vironos Street, 6023 Larnaca, Chypre',
            'site' => 'https://www.hostinger.com',
        ],
    ],

    // Route du formulaire « demande de projet » (cible de tous les CTA de l'accueil).
    'form_route' => 'demande-de-projet',

    // Utilisé par les anciennes sections (hero, contact) : conservé tel quel.
    'cta' => [
        'label' => 'Démarrer un projet',
        'href' => '#contact',
    ],

    // Navigation (résolue par App\Support\SiteNav) :
    // 1. 'route' si la route existe ; 2. sinon 'anchor' (section de l'accueil) ;
    // 3. 'requires' : clé de ce fichier qui doit être non vide (ex. avis) ;
    // 4. sans destination, le lien est masqué (aucun lien mort).
    'nav' => [
        ['label' => 'Services', 'route' => 'services'],
        ['label' => 'Projets', 'route' => 'projets', 'anchor' => 'projets'],
        ['label' => 'Parcours', 'route' => 'parcours', 'anchor' => 'parcours'],
        ['label' => 'Avis', 'route' => 'avis'],
        ['label' => 'Blog', 'route' => 'blog'],
    ],

    // Réalisations affichées sur l'accueil (images dans public/images/hero/, dimensions réelles).
    // Les descriptions sont déduites des captures : à corriger si besoin.
    'projects' => [
        ['name' => 'Evenzia', 'description' => 'Plateforme d’événements business en Afrique de l’Ouest', 'image' => 'hero-1.jpg', 'width' => 1024, 'height' => 463],
        ['name' => 'Petits Pas', 'description' => 'Activités pour enfants, choisies selon leur âge', 'image' => 'hero-3.jpg', 'width' => 1024, 'height' => 507],
        ['name' => 'Talent Club', 'description' => 'Plateforme de talents aux profils vérifiés', 'image' => 'hero-6.jpg', 'width' => 1024, 'height' => 463],
        ['name' => 'AnonyVox', 'description' => 'Sondages et avis anonymes', 'image' => 'hero-4.jpg', 'width' => 1024, 'height' => 463],
        ['name' => 'Vécu', 'description' => 'Plateforme de récits personnels', 'image' => 'hero-2.jpg', 'width' => 1024, 'height' => 463],
        ['name' => 'Trouvez votre évidence', 'description' => 'Site de rencontres sérieuses', 'image' => 'hero-5.jpg', 'width' => 1024, 'height' => 507],
    ],

    // Témoignages clients : la section « Avis » et son lien n'apparaissent que si cette liste est remplie.
    // Format : ['quote' => '…', 'name' => 'Prénom Nom', 'role' => 'Activité, ville'].
    'testimonials' => [],

    // Repères du parcours (page Parcours, frise). 'annee' accepte aussi une étiquette.
    'parcours' => [
        [
            'annee' => 'Le point de départ',
            'titre' => 'Licence en systèmes informatiques et logiciels',
            'texte' => 'Gaza Formation. Bases de données, architectures web, rigueur des algorithmes : j’apprends à construire la logique d’un site.',
        ],
        [
            'annee' => 'Le déclic',
            'titre' => 'De beaux sites qui ne vendaient pas',
            'texte' => 'Je vois des entrepreneurs investir dans des sites parfaits sur le plan visuel, mais sans message clair. Résultat : des visiteurs, et presque aucun client.',
        ],
        [
            'annee' => 'La formation',
            'titre' => 'Closeur certifié de MarketingLab Institut',
            'texte' => 'École des commerciaux du Bénin, sous la direction de M. Beni Tossou. Copywriting, publicité Facebook et closing : ce qui transforme une visite en vente.',
        ],
        [
            'annee' => 'Aujourd’hui',
            'titre' => 'Le code au service de la vente',
            'texte' => 'Je construis des sites qui guident chaque visiteur jusqu’à l’action : commander, demander un devis, prendre rendez-vous.',
        ],
    ],
];
