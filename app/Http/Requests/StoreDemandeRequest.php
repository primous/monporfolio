<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('budget_unknown')) {
            $this->merge([
                'budget_unknown' => filter_var($this->budget_unknown, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activity' => ['required', 'string', 'max:2000'],
            'has_website' => ['required', 'string', 'max:255'],
            'difficulties' => ['required', 'array', 'min:1'],
            'difficulties.*' => ['string', 'max:255'],
            'main_difficulty' => ['nullable', 'string', 'max:2000'],
            'objective' => ['required', 'string', 'max:2000'],
            'need' => ['required', 'string', 'max:2000'],
            'budget_unknown' => ['boolean'],
            'budget' => ['required_unless:budget_unknown,true', 'nullable', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string'], // honeypot
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'activity.required' => 'Décrivez votre activité en quelques mots pour continuer.',
            'activity.max' => 'La description de votre activité ne peut pas dépasser 2000 caractères.',
            'has_website.required' => 'Choisissez une réponse pour continuer.',
            'difficulties.required' => 'Cochez au moins une difficulté pour continuer.',
            'difficulties.min' => 'Cochez au moins une difficulté pour continuer.',
            'objective.required' => 'Indiquez ce que vous attendez de votre site pour continuer.',
            'objective.max' => 'L’objectif ne peut pas dépasser 2000 caractères.',
            'need.required' => 'Décrivez votre besoin en quelques lignes pour continuer.',
            'need.max' => 'La description de votre besoin ne peut pas dépasser 2000 caractères.',
            'budget.required_unless' => 'Indiquez un montant approximatif, ou cochez « Je ne sais pas encore ».',
            'full_name.required' => 'Entrez votre nom complet.',
            'email.required' => 'Entrez une adresse email valide.',
            'email.email' => 'Entrez une adresse email valide.',
            'phone.required' => 'Entrez un numéro valide, avec l’indicatif du pays.',
            'consent.accepted' => 'Cochez cette case pour envoyer votre demande.',
        ];
    }
}

