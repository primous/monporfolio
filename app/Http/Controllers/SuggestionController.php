<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Suggestion anonyme (pied de page) : aucun nom, aucune coordonnée, aucune adresse IP enregistrée.
 * Réponse JSON : 201 si enregistrée, 422 si invalide. Champ « website » = piège à robots.
 */
class SuggestionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->filled('website')) {
            return response()->json(['ok' => true], 201);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:2000'],
            'page' => ['nullable', 'string', 'max:255'],
        ], [
            'message.required' => 'Écrivez votre suggestion en quelques mots.',
            'message.min' => 'Écrivez votre suggestion en quelques mots.',
            'message.max' => 'Votre suggestion ne peut pas dépasser 2000 caractères.',
        ]);

        Suggestion::create([
            'message' => trim($data['message']),
            // On garde seulement le chemin de la page (jamais de paramètres d'URL).
            'page' => isset($data['page']) ? '/'.ltrim((string) parse_url($data['page'], PHP_URL_PATH), '/') : null,
        ]);

        return response()->json(['ok' => true], 201);
    }
}
