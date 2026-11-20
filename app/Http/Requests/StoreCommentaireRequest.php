<?php

namespace App\Http\Requests;

class StoreCommentaireRequest extends FormulairePublicRequest
{
    protected function regles(): array
    {
        return [
            'nom' => ['required', 'string', 'min:2', 'max:80'],
            'message' => ['required', 'string', 'min:3', 'max:1500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Indiquez votre prénom ou votre nom.',
            'nom.min' => 'Indiquez votre prénom ou votre nom.',
            'nom.max' => 'Votre nom ne peut pas dépasser 80 caractères.',
            'message.required' => 'Écrivez votre commentaire.',
            'message.min' => 'Écrivez votre commentaire en quelques mots.',
            'message.max' => 'Votre commentaire ne peut pas dépasser 1500 caractères.',
        ];
    }
}
