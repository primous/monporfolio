<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAvisRequest;
use App\Models\Avis;
use Illuminate\Http\RedirectResponse;

/**
 * Avis déposé par un client depuis la page Avis : enregistré « En attente »,
 * publié seulement après relecture dans l'admin. Validation : StoreAvisRequest.
 */
class AvisController extends Controller
{
    public function store(StoreAvisRequest $request): RedirectResponse
    {
        $retour = route('avis').'#laisser-un-avis';

        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->estUnRobot()) {
            return redirect()->to($retour)->with('avis_envoye', true);
        }

        $data = $request->validated();

        Avis::create([
            'nom' => trim($data['nom']),
            'activite' => filled($data['activite'] ?? null) ? trim($data['activite']) : null,
            'contenu' => trim($data['contenu']),
            'statut' => 'En attente',
        ]);

        return redirect()->to($retour)->with('avis_envoye', true);
    }
}
