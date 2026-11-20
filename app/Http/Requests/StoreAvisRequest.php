<?php

namespace App\Http\Requests;

class StoreAvisRequest extends FormulairePublicRequest
{
    protected function regles(): array
    {
        return [
            'nom' => ['required', 'string', 'min:2', 'max:80'],
            'activite' => ['nullable', 'string', 'max:120'],
            'contenu' => ['required', 'string', 'min:10', 'max:1500'],
            'accord' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Indiquez votre prénom ou votre nom.',
            'nom.min' => 'Indiquez votre prénom ou votre nom.',
            'nom.max' => 'Votre nom ne peut pas dépasser 80 caractères.',
            'activite.max' => 'Votre activité ne peut pas dépasser 120 caractères.',
            'contenu.required' => 'Écrivez votre avis.',
            'contenu.min' => 'Décrivez votre expérience en quelques mots (10 caractères minimum).',
            'contenu.max' => 'Votre avis ne peut pas dépasser 1500 caractères.',
            'accord.accepted' => 'Cochez cette case pour que votre avis puisse être publié.',
        ];
    }
}
