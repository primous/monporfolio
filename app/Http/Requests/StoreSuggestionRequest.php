<?php

namespace App\Http\Requests;

class StoreSuggestionRequest extends FormulairePublicRequest
{
    protected function regles(): array
    {
        return [
            'message' => ['required', 'string', 'min:5', 'max:2000'],
            'page' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Écrivez votre suggestion en quelques mots.',
            'message.min' => 'Écrivez votre suggestion en quelques mots.',
            'message.max' => 'Votre suggestion ne peut pas dépasser 2000 caractères.',
        ];
    }
}
