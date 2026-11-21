<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** Admin : limite de 5 échecs par 15 min, déconnexion après inactivité, liste d'IP. */
class AdminDiscreteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.admin_email' => 'admin@example.com']);
    }

    private function essai(string $motDePasse)
    {
        return Livewire::test(Login::class)
            ->fillForm(['email' => 'admin@example.com', 'password' => $motDePasse])
            ->call('authenticate');
    }

    public function test_5_echecs_bloquent_la_connexion_15_minutes(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!']);

        for ($i = 0; $i < 5; $i++) {
            $this->essai('mauvais')->assertHasFormErrors(['email']);
        }

        // 2 minutes plus tard : la limite Filament (par minute) est passée, la nôtre (15 min) tient toujours.
        $this->travel(2)->minutes();
        $this->essai('Bon-MotDePasse-2026!')->assertNotified();
        $this->assertGuest();

        // Après 15 minutes : de nouveau possible.
        $this->travel(14)->minutes();
        $this->essai('Bon-MotDePasse-2026!');
        $this->assertAuthenticated();
    }

    public function test_deconnexion_apres_15_minutes_d_inactivite(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->travel(10)->minutes();
        $this->get('/admin')->assertOk();            // activité : le délai repart

        $this->travel(16)->minutes();
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_liste_d_ip_autorisees(): void
    {
        config(['site.admin_allowed_ips' => '198.51.100.0/24']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/admin/login')->assertNotFound();
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.20'])->get('/admin/login')->assertOk();
    }

    public function test_sans_liste_d_ip_tout_le_monde_voit_la_page_de_connexion(): void
    {
        config(['site.admin_allowed_ips' => '']);
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/admin/login')->assertOk();
    }
}
