<?php

namespace Tests\Feature;

use App\Models\ArticleBlog;
use App\Models\Avis;
use App\Models\Projet;
use App\Models\Service;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_du_menu_repondent_meme_vides(): void
    {
        foreach (['home', 'services', 'projets', 'parcours', 'avis', 'blog'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_le_menu_pointe_vers_les_pages_et_plus_vers_des_ancres_mortes(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        foreach (['services', 'projets', 'parcours', 'avis', 'blog'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $html);
        }
    }

    public function test_seuls_les_contenus_publies_sont_affiches(): void
    {
        Service::create(['titre' => 'Site vitrine visible', 'description' => 'Texte.', 'publie' => true]);
        Service::create(['titre' => 'Offre cachée', 'description' => 'Texte.', 'publie' => false]);
        Avis::create(['nom' => 'Awa', 'contenu' => 'Avis publié', 'statut' => 'Publié']);
        Avis::create(['nom' => 'Koffi', 'contenu' => 'Avis en attente', 'statut' => 'En attente']);
        Projet::create(['titre' => 'Projet caché', 'description' => 'Texte.', 'publie' => false]);

        $this->get(route('services'))->assertSee('Site vitrine visible')->assertDontSee('Offre cachée');
        $this->get(route('avis'))->assertSee('Avis publié')->assertDontSee('Avis en attente');
        $this->get(route('projets'))->assertDontSee('Projet caché');
    }

    public function test_un_article_publie_est_lisible_et_un_brouillon_renvoie_404(): void
    {
        ArticleBlog::create(['titre' => 'Mon premier article', 'contenu' => '<p>Bonjour</p>', 'statut' => 'Publié']);
        ArticleBlog::create(['titre' => 'Brouillon secret', 'contenu' => '<p>Non</p>', 'statut' => 'Brouillon']);

        $this->get(route('blog'))->assertSee('Mon premier article')->assertDontSee('Brouillon secret');
        $this->get(route('blog.article', 'mon-premier-article'))->assertOk()->assertSee('Bonjour', false);
        $this->get(route('blog.article', 'brouillon-secret'))->assertNotFound();
    }

    public function test_une_suggestion_valide_est_enregistree_sans_donnee_personnelle(): void
    {
        $this->postJson(route('suggestion.store'), [
            'message' => 'Ajoutez vos tarifs indicatifs.',
            'page' => '/services?utm=pub',
        ])->assertCreated()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('suggestions', [
            'message' => 'Ajoutez vos tarifs indicatifs.',
            'page' => '/services', // paramètres d'URL retirés
            'lu_at' => null,
        ]);
    }

    public function test_une_suggestion_vide_retourne_422(): void
    {
        $this->postJson(route('suggestion.store'), ['message' => ' '])
            ->assertStatus(422)->assertJsonValidationErrors('message');

        $this->assertDatabaseCount('suggestions', 0);
    }

    public function test_le_piege_a_robots_fait_semblant_sans_enregistrer(): void
    {
        $this->postJson(route('suggestion.store'), ['message' => 'Spam spam spam', 'website' => 'http://spam.test'])
            ->assertCreated();

        $this->assertSame(0, Suggestion::count());
    }
}
