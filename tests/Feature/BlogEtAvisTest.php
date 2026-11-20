<?php

namespace Tests\Feature;

use App\Models\ArticleBlog;
use App\Models\Avis;
use App\Models\CategorieBlog;
use App\Models\CommentaireBlog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogEtAvisTest extends TestCase
{
    use RefreshDatabase;

    private function article(array $attrs = []): ArticleBlog
    {
        return ArticleBlog::create([
            'titre' => 'Un site qui vend',
            'contenu' => '<p>Contenu</p>',
            'statut' => 'Publié',
            'date_publication' => now()->subDay(),
            ...$attrs,
        ]);
    }

    public function test_une_lecture_est_comptee_une_seule_fois_par_session(): void
    {
        $article = $this->article();

        $this->get(route('blog.article', $article->slug))->assertOk();
        $this->get(route('blog.article', $article->slug))->assertOk();

        $this->assertSame(1, $article->fresh()->vues);
    }

    public function test_les_robots_ne_sont_pas_comptes(): void
    {
        $article = $this->article();

        $this->withHeader('User-Agent', 'Googlebot/2.1')->get(route('blog.article', $article->slug))->assertOk();

        $this->assertSame(0, $article->fresh()->vues);
    }

    public function test_les_liens_de_partage_sont_presents(): void
    {
        $article = $this->article();

        $this->get(route('blog.article', $article->slug))
            ->assertSee('facebook.com/sharer/sharer.php', false)
            ->assertSee('wa.me/?text=', false)
            ->assertSee('Copier le lien');
    }

    public function test_le_filtre_par_categorie(): void
    {
        $strategie = CategorieBlog::create(['nom' => 'Stratégie']);
        $confiance = CategorieBlog::create(['nom' => 'Confiance']);
        $this->article(['titre' => 'Article stratégie', 'categorie_blog_id' => $strategie->id]);
        $this->article(['titre' => 'Article confiance', 'categorie_blog_id' => $confiance->id]);

        $this->get(route('blog', ['categorie' => 'strategie']))
            ->assertOk()->assertSee('Article stratégie')->assertDontSee('Article confiance');
    }

    public function test_un_commentaire_arrive_en_attente_et_n_est_pas_affiche(): void
    {
        $article = $this->article();

        $this->post(route('blog.commentaire', $article->slug), ['nom' => 'Awa', 'message' => 'Très clair, merci.'])
            ->assertRedirect(route('blog.article', $article->slug).'#commentaires');

        $this->assertDatabaseHas('commentaires_blog', ['nom' => 'Awa', 'statut' => 'En attente']);
        $this->get(route('blog.article', $article->slug))->assertDontSee('Très clair, merci.');
    }

    public function test_un_commentaire_publie_est_affiche(): void
    {
        $article = $this->article();
        CommentaireBlog::create(['article_blog_id' => $article->id, 'nom' => 'Koffi', 'message' => 'Article utile.', 'statut' => 'Publié']);

        $this->get(route('blog.article', $article->slug))->assertSee('Article utile.')->assertSee('Koffi');
    }

    public function test_un_commentaire_vide_est_refuse(): void
    {
        $article = $this->article();

        $this->post(route('blog.commentaire', $article->slug), ['nom' => '', 'message' => ''])
            ->assertSessionHasErrors(['nom', 'message']);

        $this->assertDatabaseCount('commentaires_blog', 0);
    }

    public function test_un_avis_depose_arrive_en_attente_avec_accord_obligatoire(): void
    {
        $this->post(route('avis.store'), ['nom' => 'Mariam', 'contenu' => 'Mon site est enfin clair pour mes clientes.'])
            ->assertSessionHasErrors('accord');
        $this->assertDatabaseCount('avis', 0);

        $this->post(route('avis.store'), ['nom' => 'Mariam', 'activite' => 'Boutique, Cotonou', 'contenu' => 'Mon site est enfin clair pour mes clientes.', 'accord' => '1'])
            ->assertRedirect(route('avis').'#laisser-un-avis');

        $this->assertDatabaseHas('avis', ['nom' => 'Mariam', 'statut' => 'En attente']);
        $this->get(route('avis'))->assertDontSee('Mon site est enfin clair pour mes clientes.');
    }

    public function test_le_piege_a_robots_des_avis_n_enregistre_rien(): void
    {
        $this->post(route('avis.store'), ['nom' => 'Bot', 'contenu' => 'Spam spam spam spam', 'accord' => '1', 'website' => 'x'])
            ->assertRedirect();

        $this->assertSame(0, Avis::count());
    }
}
