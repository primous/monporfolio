<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base des formulaires publics (avis, commentaire, suggestion, contact).
 *
 * Piège à robots : un champ caché « website » que seuls les robots remplissent.
 * S'il est rempli, aucune règle n'est appliquée (pas de 422 qui renseignerait le robot) :
 * le contrôleur feint alors le succès sans rien enregistrer.
 */
abstract class FormulairePublicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // formulaires ouverts aux visiteurs, limités par throttle sur la route
    }

    public function estUnRobot(): bool
    {
        return $this->filled('website');
    }

    final public function rules(): array
    {
        return $this->estUnRobot() ? [] : $this->regles();
    }

    /** @return array<string, mixed> */
    abstract protected function regles(): array;
}
