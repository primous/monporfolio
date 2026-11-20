<?php

namespace Database\Seeders;

use App\Models\ArticleBlog;
use App\Models\CategorieBlog;
use Illuminate\Database\Seeder;

/**
 * Catégories du blog + rattachement des 6 articles existants. Idempotent.
 */
class CategorieBlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Stratégie' => [
                'votre-site-internet-a-un-seul-travail-faire-agir-vos-visiteurs',
                'pourquoi-un-beau-site-ne-suffit-pas-a-vendre',
            ],
            'Parcours client' => [
                'les-7-etapes-qui-transforment-un-visiteur-en-client',
                'apres-le-clic-formulaire-transmission-et-suivi',
            ],
            'Confiance' => [
                'ce-qui-rassure-un-visiteur-avant-de-passer-a-laction',
            ],
            'Exemples par activité' => [
                'restaurant-boutique-prestataire-a-chaque-activite-son-parcours',
            ],
        ];

        $ordre = 0;
        foreach ($categories as $nom => $slugs) {
            $categorie = CategorieBlog::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($nom)],
                ['nom' => $nom, 'ordre_affichage' => ++$ordre],
            );

            ArticleBlog::whereIn('slug', $slugs)->update(['categorie_blog_id' => $categorie->id]);
        }
    }
}
