<?php

namespace Tests\Feature;

use App\Models\ArticleBlog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Aucune adresse e-mail en clair dans le HTML ; pages d'erreur sans signature Laravel. */
class DiscretionTest extends TestCase
{
    use RefreshDatabase;

    public function test_aucune_adresse_e_mail_en_clair_sur_les_pages_publiques(): void
    {
        config(['site.email' => 'contact@exemple-site.com']);
        $article = ArticleBlog::create(['titre' => 'Un article', 'contenu' => '<p>x</p>', 'statut' => 'Publié', 'date_publication' => now()->subDay()]);

        $pages = ['/', '/services', '/projets', '/parcours', '/avis', '/blog', '/demande-de-projet',
            '/mentions-legales', '/politique-confidentialite', '/blog/'.$article->slug];

        foreach ($pages as $page) {
            $html = $this->get($page)->assertOk()->getContent();
            $this->assertStringNotContainsString('contact@exemple-site.com', $html, "E-mail en clair sur $page");
            $this->assertDoesNotMatchRegularExpression('/mailto:[^\'"\s]+@/', $html, "mailto: avec adresse sur $page");
        }
    }

    public function test_l_e_mail_protege_se_reconstitue_cote_navigateur(): void
    {
        config(['site.email' => 'contact@exemple-site.com']);
        $html = $this->get('/mentions-legales')->getContent();

        preg_match('/data-e="([^"]+)"/', $html, $m);
        $this->assertSame('contact@exemple-site.com', strrev(base64_decode($m[1])));
    }

    public function test_les_pages_d_erreur_ne_trahissent_pas_laravel(): void
    {
        $html = $this->get('/page-inexistante')->assertNotFound()->getContent();

        $this->assertStringContainsString('page introuvable', $html);
        $this->assertStringContainsString('noindex', $html);
        $this->assertStringNotContainsStringIgnoringCase('laravel', $html);
        $this->assertStringNotContainsString('Not Found', $html);
    }
}
