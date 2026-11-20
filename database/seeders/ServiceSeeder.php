<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Les 5 compétences présentées sur les visuels « Mes compétences » et « Qui suis-je ».
 * Le site internet passe en premier : c'est l'objet du site. Idempotent (clé : titre).
 */
class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'titre' => 'Création de site internet',
                'description' => 'Un site moderne et adapté au téléphone, construit pour guider votre visiteur jusqu’à l’action : commander, demander un devis ou vous écrire.',
                'points' => ['Sites modernes et responsives', 'Tunnels de vente qui convertissent', 'Landing pages optimisées', 'Blogs et portfolios professionnels'],
            ],
            [
                'titre' => 'Copywriting',
                'description' => 'Des textes qui déclenchent l’achat. Votre offre est expliquée avec des mots qui accrochent, rassurent et donnent envie d’agir.',
                'points' => ['Accrocher dès les premières lignes', 'Créer du désir et de la confiance', 'Des messages qui persuadent', 'Transformer les mots en ventes'],
            ],
            [
                'titre' => 'Facebook Ads',
                'description' => 'De zéro à une campagne rentable : vos publicités touchent les bonnes personnes et les amènent vers votre site.',
                'points' => ['Ciblage précis', 'Annonces qui convertissent', 'Optimisation pour maximiser le retour sur investissement'],
            ],
            [
                'titre' => 'Affiches publicitaires avec l’IA',
                'description' => 'Des visuels percutants pour vos réseaux et vos publicités, sans être designer : un message clair, prêt à attirer et à convertir.',
                'points' => ['Visuels qui attirent l’attention', 'Messages clairs et impactants', 'Affiches prêtes à convertir'],
            ],
            [
                'titre' => 'Création de contenu',
                'description' => 'Les bases qui rendent tout le reste plus efficace : des contenus qui attirent, font réagir et amènent vos abonnés à passer à l’action.',
                'points' => ['Contenu qui attire', 'Contenu qui engage', 'Contenu qui fait passer à l’action'],
            ],
        ];

        foreach ($services as $ordre => $service) {
            Service::updateOrCreate(
                ['titre' => $service['titre']],
                [...$service, 'ordre_affichage' => $ordre + 1, 'publie' => true],
            );
        }
    }
}
