<?php

namespace Tests\Feature;

use App\Models\ArticleBlog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleSanitizeTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_contenu_d_un_article_est_nettoye_a_l_affichage(): void
    {
        $article = ArticleBlog::create([
            'titre' => 'Article piégé',
            'contenu' => '<p>Texte <strong>gras</strong></p><script>alert(1)</script><img src="x" onerror="alert(2)"><a href="javascript:alert(3)">lien</a>',
            'statut' => 'Publié',
            'date_publication' => now()->subDay(),
        ]);

        $html = $this->get(route('blog.article', $article->slug))->assertOk()->getContent();

        $this->assertStringContainsString('<strong>gras</strong>', $html);
        $this->assertStringNotContainsString('alert(1)', $html);
        $this->assertStringNotContainsString('onerror', $html);
        $this->assertStringNotContainsString('javascript:alert(3)', $html);
    }
}
