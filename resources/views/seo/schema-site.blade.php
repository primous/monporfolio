@php
    // Entreprise (ProfessionalService, sous-type de LocalBusiness) + fondateur + site web.
    // NAP : mêmes nom, ville et téléphone que sur Google Business Profile et les réseaux.
    $base = url('/');
    $legal = config('site.legal');
    $telephone = config('site.phone');
    $sameAs = array_values(array_filter(config('site.social', [])));

    $entreprise = array_filter([
        '@type' => 'ProfessionalService',
        '@id' => $base.'/#entreprise',
        'name' => 'Hounkpatin Primous',
        'alternateName' => 'Primous',
        'slogan' => 'Votre référence digitale préférée',
        'description' => 'Création de sites internet, landing pages, copywriting et publicité Facebook pour commerçants, artisans et indépendants à Cotonou et au Bénin.',
        'url' => $base,
        'logo' => asset('images/brand/logo.png'),
        'image' => asset('images/brand/og-default.jpg'),
        'email' => config('site.email'),
        'telephone' => $telephone,
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $legal['adresse'] ?? null,
            'addressLocality' => 'Cotonou',
            'addressCountry' => 'BJ',
        ]),
        'areaServed' => [
            ['@type' => 'City', 'name' => 'Cotonou'],
            ['@type' => 'Country', 'name' => 'Bénin'],
        ],
        'knowsAbout' => ['Création de site internet', 'Landing page', 'Copywriting', 'Facebook Ads', 'Création de contenu'],
        'founder' => ['@id' => $base.'/#fondateur'],
        'sameAs' => $sameAs ?: null,
    ]);

    $graph = [
        $entreprise,
        [
            '@type' => 'Person',
            '@id' => $base.'/#fondateur',
            'name' => 'Hounkpatin Primous',
            'jobTitle' => 'Créateur de sites internet',
            'image' => asset('images/about/primous.jpg'),
            'url' => route('parcours'),
            'worksFor' => ['@id' => $base.'/#entreprise'],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $base.'/#site',
            'url' => $base,
            'name' => 'Hounkpatin Primous',
            'inLanguage' => 'fr',
            'publisher' => ['@id' => $base.'/#entreprise'],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
