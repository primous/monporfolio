<?php

namespace App\Http\Requests;

class StoreContactRequest extends FormulairePublicRequest
{
    protected function regles(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'max' => 'Le champ :attribute est trop long.',
            'message.min' => 'Décrivez votre projet en quelques mots (10 caractères minimum).',
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nom', 'email' => 'e-mail', 'message' => 'projet'];
    }
}
