<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

/** Double authentification de l'admin : le mot de passe seul ne suffit plus. */
class DoubleAuthentificationTest extends TestCase
{
    use RefreshDatabase;

    private Google2FA $g2fa;

    private string $secret;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.admin_email' => 'admin@example.com']);
        $this->g2fa = new Google2FA;
        $this->secret = $this->g2fa->generateSecretKey();
        $admin = User::factory()->create(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!']);
        $admin->saveAppAuthenticationSecret($this->secret);
        $admin->save();
    }

    private function etapeMotDePasse()
    {
        return Livewire::test(Login::class)
            ->fillForm(['email' => 'admin@example.com', 'password' => 'Bon-MotDePasse-2026!'])
            ->call('authenticate');
    }

    public function test_le_secret_est_chiffre_en_base(): void
    {
        $brut = \DB::table('users')->value('app_authentication_secret');
        $this->assertNotSame($this->secret, $brut);
        $this->assertSame($this->secret, User::first()->getAppAuthenticationSecret());
        $this->assertArrayNotHasKey('app_authentication_secret', User::first()->toArray());
    }

    public function test_le_bon_mot_de_passe_seul_ne_connecte_pas(): void
    {
        $this->etapeMotDePasse()->assertSet('userUndertakingMultiFactorAuthentication', fn ($v) => filled($v));
        $this->assertGuest();
    }

    public function test_un_mauvais_code_est_refuse(): void
    {
        $this->etapeMotDePasse()
            ->set('data.multiFactor.app.code', '000000')
            ->call('authenticate');
        $this->assertGuest();
    }

    public function test_le_bon_code_connecte(): void
    {
        $this->etapeMotDePasse()
            ->set('data.multiFactor.app.code', $this->g2fa->getCurrentOtp($this->secret))
            ->call('authenticate');
        $this->assertAuthenticated();
    }
}
