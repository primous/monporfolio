<?php

namespace Tests\Feature;

use App\Models\Avis;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormulairesPublicsTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_avis_invalide_est_refuse_avec_le_message_francais(): void
    {
        $this->from(route('avis'))->post(route('avis.store'), ['nom' => 'A', 'contenu' => 'court'])
            ->assertSessionHasErrors(['nom' => 'Indiquez votre prénom ou votre nom.', 'accord']);
        $this->assertSame(0, Avis::count());
    }

    public function test_un_robot_recoit_un_faux_succes_et_rien_n_est_enregistre(): void
    {
        $this->post(route('avis.store'), ['website' => 'http://spam.example'])
            ->assertRedirect()->assertSessionHas('avis_envoye');
        $this->postJson(route('suggestion.store'), ['website' => 'x'])->assertCreated();

        $this->assertSame(0, Avis::count());
        $this->assertSame(0, Suggestion::count());
    }

    public function test_une_suggestion_valide_est_enregistree_sans_parametres_d_url(): void
    {
        $this->postJson(route('suggestion.store'), ['message' => 'Ajoutez des prix', 'page' => '/services?utm_source=x'])
            ->assertCreated();
        $this->assertSame('/services', Suggestion::first()->page);
    }
}
