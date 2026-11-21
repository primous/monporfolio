<?php

namespace Tests\Feature;

use App\Filament\Resources\Demandes\Pages\ListDemandes;
use App\Models\ContactMessage;
use App\Models\Demande;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** Droit à l'effacement (admin seulement) et absence de données de test en production. */
class DonneesPersonnellesTest extends TestCase
{
    use RefreshDatabase;

    private function demande(string $email): Demande
    {
        return Demande::create([
            'activity' => 'Boutique', 'has_website' => 'non', 'difficulties' => ['x'], 'objective' => 'Vendre',
            'need' => 'Un site', 'budget_unknown' => true, 'full_name' => 'Client', 'email' => $email,
            'phone' => '+22900000000', 'consent' => true, 'consent_at' => now(), 'statut' => 'nouveau',
        ]);
    }

    public function test_l_admin_efface_toutes_les_donnees_d_une_personne_et_rien_d_autre(): void
    {
        config(['site.admin_email' => 'admin@example.com']);
        $this->demande('ama@exemple.com');
        $this->demande('AMA@exemple.com');
        $this->demande('koffi@exemple.com');
        ContactMessage::create(['name' => 'Ama', 'email' => 'ama@exemple.com', 'message' => 'Bonjour à vous']);

        $this->actingAs(User::factory()->create(['email' => 'admin@example.com']));
        Livewire::test(ListDemandes::class)
            ->callAction('effacerDonnees', data: ['email' => 'ama@exemple.com', 'confirmation' => 'EFFACER'])
            ->assertHasNoActionErrors()
            ->assertNotified('Données effacées');

        $this->assertSame(['koffi@exemple.com'], Demande::pluck('email')->all());
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_sans_confirmation_rien_n_est_efface(): void
    {
        config(['site.admin_email' => 'admin@example.com']);
        $this->demande('ama@exemple.com');

        $this->actingAs(User::factory()->create(['email' => 'admin@example.com']));
        Livewire::test(ListDemandes::class)
            ->callAction('effacerDonnees', data: ['email' => 'ama@exemple.com', 'confirmation' => 'oui'])
            ->assertHasActionErrors(['confirmation']);

        $this->assertSame(1, Demande::count());
    }

    public function test_la_commande_artisan_efface_apres_confirmation(): void
    {
        $this->demande('ama@exemple.com');

        $this->artisan('donnees:effacer', ['email' => 'ama@exemple.com'])
            ->expectsConfirmation('Supprimer définitivement ces données ?', 'no')
            ->assertSuccessful();
        $this->assertSame(1, Demande::count());

        $this->artisan('donnees:effacer', ['email' => 'ama@exemple.com', '--force' => true])->assertSuccessful();
        $this->assertSame(0, Demande::count());
    }

    public function test_aucune_route_publique_ne_permet_d_effacer(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            $this->assertStringNotContainsStringIgnoringCase('effacer', $route->uri());
        }
    }

    public function test_le_seeder_ne_cree_pas_le_compte_de_test_en_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->app->make(DatabaseSeeder::class)->run();
        $this->assertTrue(app()->isProduction());
        $this->assertSame(0, User::where('email', 'test@example.com')->count());
    }
}
