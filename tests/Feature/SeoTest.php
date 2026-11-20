<?php

namespace Tests\Feature;

use App\Models\ArticleBlog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_sitemap_liste_les_pages_et_les_articles_publies_uniquement(): void
    {
        ArticleBlog::create(['titre' => 'Article publié', 'contenu' => '<p>x</p>', 'statut' => 'Publié', 'date_publication' => now()->subDay()]);
        ArticleBlog::create(['titre' => 'Brouillon caché', 'contenu' => '<p>x</p>', 'statut' => 'Brouillon']);

        $res = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = simplexml_load_string($res->getContent());

        $this->assertNotFalse($xml, 'XML invalide');
        // On compare les chemins : l'hôte dépend de la requête (localhost en test, le domaine en ligne).
        $chemins = [];
        foreach ($xml->url as $u) {
            $chemins[] = parse_url((string) $u->loc, PHP_URL_PATH) ?? '/';
        }
        $this->assertContains('/services', $chemins);
        $this->assertContains('/blog/article-publie', $chemins);
        $this->assertNotContains('/blog/brouillon-cache', $chemins);
    }

    public function test_robots_txt_bloque_l_admin_et_annonce_le_sitemap(): void
    {
        $this->get('/robots.txt')->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_canonical_sans_parametres_de_suivi_et_balises_de_partage(): void
    {
        $html = $this->get('/services?utm_source=facebook&fbclid=abc')->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="'.route('services').'">', $html);
        $this->assertStringContainsString('property="og:image"', $html);
        $this->assertStringContainsString('name="twitter:card" content="summary_large_image"', $html);
        $this->assertFileExists(public_path('images/brand/og-default.jpg'));
    }

    public function test_titres_et_descriptions_aux_bonnes_longueurs(): void
    {
        foreach (['home', 'services', 'projets', 'parcours', 'avis', 'blog', 'demande-de-projet'] as $route) {
            $html = $this->get(route($route))->getContent();
            preg_match('/<title>(.*?)<\/title>/s', $html, $t);
            preg_match('/<meta name="description" content="([^"]*)"/', $html, $d);
            $titre = html_entity_decode($t[1], ENT_QUOTES);
            $desc = html_entity_decode($d[1], ENT_QUOTES);

            $this->assertTrue(mb_strlen($titre) >= 50 && mb_strlen($titre) <= 60, "Titre de « $route » : ".mb_strlen($titre).' caractères');
            $this->assertTrue(mb_strlen($desc) >= 140 && mb_strlen($desc) <= 155, "Description de « $route » : ".mb_strlen($desc).' caractères');
        }
    }

    public function test_donnees_structurees_valides(): void
    {
        $article = ArticleBlog::create(['titre' => 'Un article', 'contenu' => '<p>x</p>', 'statut' => 'Publié', 'date_publication' => now()->subDay()]);

        $types = function (string $url) {
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $this->get($url)->getContent(), $m);
            $out = [];
            foreach ($m[1] as $json) {
                $data = json_decode($json, true);
                $this->assertIsArray($data, 'JSON-LD invalide sur '.$url);
                foreach ($data['@graph'] ?? [$data] as $node) {
                    $out[] = $node['@type'];
                }
            }

            return $out;
        };

        $this->assertEqualsCanonicalizing(['ProfessionalService', 'Person', 'WebSite', 'FAQPage'], $types(route('home')));
        $this->assertContains('BreadcrumbList', $types(route('services')));
        $this->assertContains('BlogPosting', $types(route('blog.article', $article->slug)));
    }
}
