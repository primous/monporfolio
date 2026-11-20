<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Avis déposé par un client depuis la page Avis : enregistré « En attente »,
 * publié seulement après relecture dans l'admin. Champ « website » = piège à robots.
 */
class AvisController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $retour = route('avis').'#laisser-un-avis';

        if ($request->filled('website')) {
            return redirect()->to($retour)->with('avis_envoye', true);
        }

        $data = $request->validate([
            'nom' => ['required', 'string', 'min:2', 'max:80'],
            'activite' => ['nullable', 'string', 'max:120'],
            'contenu' => ['required', 'string', 'min:10', 'max:1500'],
            'accord' => ['accepted'],
        ], [
            'nom.required' => 'Indiquez votre prénom ou votre nom.',
            'nom.min' => 'Indiquez votre prénom ou votre nom.',
            'nom.max' => 'Votre nom ne peut pas dépasser 80 caractères.',
            'activite.max' => 'Votre activité ne peut pas dépasser 120 caractères.',
            'contenu.required' => 'Écrivez votre avis.',
            'contenu.min' => 'Décrivez votre expérience en quelques mots (10 caractères minimum).',
            'contenu.max' => 'Votre avis ne peut pas dépasser 1500 caractères.',
            'accord.accepted' => 'Cochez cette case pour que votre avis puisse être publié.',
        ]);

        Avis::create([
            'nom' => trim($data['nom']),
            'activite' => filled($data['activite'] ?? null) ? trim($data['activite']) : null,
            'contenu' => trim($data['contenu']),
            'statut' => 'En attente',
        ]);

        return redirect()->to($retour)->with('avis_envoye', true);
    }
}
