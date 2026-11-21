<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Avis;
use App\Models\Projet;
use App\Models\Service;
use Illuminate\Http\Response;

/**
 * sitemap.xml et robots.txt générés à partir du domaine courant (APP_URL en production),
 * pour rester justes en local comme en ligne.
 */
class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $derniere = fn (string $modele) => optional($modele::query()->latest('updated_at')->first())->updated_at;
        $blogMaj = ArticleBlog::publies()->max('updated_at');

        // [route, priorité, fréquence, dernière modification]
        $pages = [
            ['home', '1.0', 'weekly', $blogMaj],
            ['services', '0.9', 'monthly', $derniere(Service::class)],
            ['demande-de-projet', '0.9', 'yearly', null],
            ['projets', '0.8', 'monthly', $derniere(Projet::class)],
            ['blog', '0.8', 'weekly', $blogMaj],
            ['parcours', '0.6', 'yearly', null],
            ['avis', '0.6', 'monthly', $derniere(Avis::class)],
            ['mentions-legales', '0.2', 'yearly', null],
            ['politique-confidentialite', '0.2', 'yearly', null],
        ];

        $urls = collect($pages)->map(fn ($p) => [
            'loc' => route($p[0]),
            'priority' => $p[1],
            'changefreq' => $p[2],
            'lastmod' => $p[3] ? \Illuminate\Support\Carbon::parse($p[3])->toAtomString() : null,
        ]);

        foreach (ArticleBlog::publies()->get() as $article) {
            $urls->push([
                'loc' => route('blog.article', $article->slug),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $article->updated_at?->toAtomString(),
            ]);
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lignes = [
            'User-agent: *',
            'Allow: /',
            // Chemins techniques. La vraie adresse de l'admin (ADMIN_PATH) n'est JAMAIS listée ici :
            // robots.txt est public, il servirait de carte aux curieux. Elle est protégée par X-Robots-Tag.
            // Les lignes /admin/, /.env, /.git/… sont des leurres : ces chemins renvoient 404 et sont journalisés.
            'Disallow: /admin/',
            'Disallow: /login/',
            'Disallow: /.env',
            'Disallow: /.git/',
            'Disallow: /backup/',
            'Disallow: /storage/',
            'Disallow: /config/',
            'Disallow: /livewire',
            'Disallow: /suggestion',
            'Disallow: /contact',
            'Disallow: /*?page=',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lignes)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
