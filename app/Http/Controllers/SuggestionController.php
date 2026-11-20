<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuggestionRequest;
use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;

/**
 * Suggestion anonyme (pied de page) : aucun nom, aucune coordonnée, aucune adresse IP enregistrée.
 * Réponse JSON : 201 si enregistrée, 422 si invalide. Validation : StoreSuggestionRequest.
 */
class SuggestionController extends Controller
{
    public function store(StoreSuggestionRequest $request): JsonResponse
    {
        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->estUnRobot()) {
            return response()->json(['ok' => true], 201);
        }

        $data = $request->validated();

        Suggestion::create([
            'message' => trim($data['message']),
            // On garde seulement le chemin de la page (jamais de paramètres d'URL).
            'page' => isset($data['page']) ? '/'.ltrim((string) parse_url($data['page'], PHP_URL_PATH), '/') : null,
        ]);

        return response()->json(['ok' => true], 201);
    }
}
