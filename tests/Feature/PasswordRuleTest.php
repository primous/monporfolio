<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class PasswordRuleTest extends TestCase
{
    private function valide(string $motDePasse): bool
    {
        return Validator::make(['password' => $motDePasse], ['password' => [Password::defaults()]])->passes();
    }

    public function test_les_mots_de_passe_faibles_sont_refuses(): void
    {
        $this->assertFalse($this->valide('Court1!'));            // moins de 12 caractères
        $this->assertFalse($this->valide('toutenminuscule1!'));  // pas de majuscule
        $this->assertFalse($this->valide('SansChiffreIci!!'));   // pas de chiffre
        $this->assertFalse($this->valide('SansSymbole12345'));   // pas de symbole
    }

    public function test_un_mot_de_passe_robuste_est_accepte(): void
    {
        $this->assertTrue($this->valide('Cotonou-Site-2026!x'));
    }
}
