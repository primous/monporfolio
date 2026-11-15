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
];
