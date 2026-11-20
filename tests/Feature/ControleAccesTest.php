<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** Contrôle d'accès à l'admin (seul SITE_ADMIN_EMAIL entre) et limites d'envoi. */
class ControleAccesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.admin_email' => 'admin@example.com']);
    }

    public function test_un_invite_est_renvoye_vers_la_connexion_sur_toutes_les_pages_admin(): void
    {
        foreach (['/admin', '/admin/demandes', '/admin/suggestions', '/admin/article-blogs', '/admin/profile'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
    }

    public function test_un_autre_compte_ne_voit_aucune_donnee_de_l_admin(): void
    {
        $intrus = User::factory()->create(['email' => 'intrus@example.com']);
        $demande = Demande::create([
            'activity' => 'Boutique', 'has_website' => 'non', 'difficulties' => ['x'], 'objective' => 'Vendre',
            'need' => 'Un site', 'budget_unknown' => true, 'full_name' => 'Client Secret', 'email' => 'client@example.com',
            'phone' => '+22900000000', 'consent' => true, 'consent_at' => now(), 'statut' => 'nouveau',
        ]);

        foreach (['/admin', '/admin/demandes', "/admin/demandes/{$demande->id}"] as $url) {
            $reponse = $this->actingAs($intrus)->get($url);
            $reponse->assertForbidden();
            $this->assertStringNotContainsString('Client Secret', $reponse->getContent());
        }
    }

    public function test_l_administrateur_accede_a_l_admin_quelle_que_soit_la_casse(): void
    {
        $admin = User::factory()->create(['email' => 'Admin@Example.com']);
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_sans_admin_configure_personne_n_entre(): void
    {
        config(['site.admin_email' => '']);
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $this->actingAs($admin)->get('/admin')->assertForbidden();
    }

    public function test_la_connexion_admin_est_limitee_a_5_essais(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!']);

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->fillForm(['email' => 'admin@example.com', 'password' => 'mauvais'])
                ->call('authenticate')
                ->assertHasFormErrors(['email']);
        }

        // 6e essai, même avec le bon mot de passe : bloqué, pas de connexion.
        Livewire::test(Login::class)
            ->fillForm(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!'])
            ->call('authenticate')
            ->assertNotified();
        $this->assertGuest();
    }

    public function test_le_message_d_echec_ne_revele_pas_si_l_e_mail_existe(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!']);

        $existe = Livewire::test(Login::class)->fillForm(['email' => 'admin@example.com', 'password' => 'faux'])
            ->call('authenticate')->errors()->get('data.email');
        $inconnu = Livewire::test(Login::class)->fillForm(['email' => 'personne@example.com', 'password' => 'faux'])
            ->call('authenticate')->errors()->get('data.email');

        $this->assertSame($existe, $inconnu);
    }

    public function test_les_formulaires_publics_sont_limites_a_5_envois_par_10_minutes(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('suggestion.store'), ['message' => "Suggestion numéro $i"])->assertCreated();
        }
        $this->postJson(route('suggestion.store'), ['message' => 'Une de trop'])->assertStatus(429);
    }
}
