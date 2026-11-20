<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class LegalEtProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_legales_repondent_et_sont_liees_dans_le_pied_de_page(): void
    {
        $this->get(route('mentions-legales'))->assertOk()->assertSee('Éditeur du site')->assertSee('Hébergement');
        $this->get(route('politique-confidentialite'))->assertOk()->assertSee('Vos droits')->assertSee('Cookies');

        $html = $this->get(route('home'))->getContent();
        $this->assertStringContainsString('href="'.route('mentions-legales').'"', $html);
        $this->assertStringContainsString('href="'.route('politique-confidentialite').'"', $html);
    }

    public function test_le_formulaire_de_demande_renvoie_a_la_politique(): void
    {
        $this->get(route('demande-de-projet'))->assertSee(route('politique-confidentialite'), false);
    }

    public function test_les_messages_de_validation_sont_en_francais(): void
    {
        $this->assertSame('fr', app()->getLocale());
        $this->assertSame('Le champ adresse e-mail est obligatoire.', __('validation.required', ['attribute' => __('validation.attributes.email')]));
    }

    public function test_l_admin_refuse_un_autre_e_mail_que_celui_autorise(): void
    {
        config(['site.admin_email' => 'admin@example.com']);
        $panel = Filament::getPanel('admin');

        $this->assertTrue(User::factory()->make(['email' => 'ADMIN@example.com'])->canAccessPanel($panel));
        $this->assertFalse(User::factory()->make(['email' => 'autre@example.com'])->canAccessPanel($panel));

        config(['site.admin_email' => '']);
        $this->assertFalse(User::factory()->make(['email' => 'admin@example.com'])->canAccessPanel($panel));
    }

    public function test_la_page_profil_change_le_mot_de_passe_avec_le_mot_de_passe_actuel(): void
    {
        config(['site.admin_email' => 'admin@example.com']);
        $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'AncienMotDePasse1']);

        $this->actingAs($user);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $this->get('/admin/profile')->assertOk();

        Livewire::test(EditProfile::class)
            ->fillForm([
                'password' => 'Nouveau-MotDePasse-2!',
                'passwordConfirmation' => 'Nouveau-MotDePasse-2!',
                'currentPassword' => 'AncienMotDePasse1',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Hash::check('Nouveau-MotDePasse-2!', $user->fresh()->password));
        $this->assertSame('admin@example.com', $user->fresh()->email);
    }
}
