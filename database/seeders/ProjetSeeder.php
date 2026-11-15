<?php

namespace Database\Seeders;

use App\Models\Projet;
use Illuminate\Database\Seeder;

/**
 * Les 6 projets déjà présentés sur l'accueil (config/site.php → projects).
 * Idempotent : ne recrée pas un projet dont le slug existe déjà.
 */
class ProjetSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('site.projects', []) as $ordre => $p) {
            Projet::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($p['name'])],
                [
                    'titre' => $p['name'],
                    'resume' => $p['description'],
                    'description' => $p['description'].'.',
                    'visuel' => 'projets/'.$p['image'], // copié dans public/images/uploads/projets (disque « site »)
                    'ordre_affichage' => $ordre + 1,
                    'publie' => true,
                ],
            );
        }
    }
}
