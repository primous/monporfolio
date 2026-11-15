<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Notifications\ConfirmationDemande;
use App\Notifications\NouvelleDemande;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class DemandeController extends Controller
{
    /**
     * Store a newly created demande de projet in storage.
     */
    public function store(StoreDemandeRequest $request): JsonResponse
    {
        // Anti-spam Honeypot : si le champ 'website' est rempli par un robot, on feint le succès
        if ($request->filled('website')) {
            Log::info('Demande spam détectée via honeypot.');
            return response()->json(['ok' => true], 201);
        }

        $validated = $request->validated();
        unset($validated['website']);

        // 1) Enregistre la demande en base de données
        $demande = Demande::create([
            ...$validated,
            'consent_at' => now(),
            'statut' => 'nouveau',
        ]);

        // 2) Envoie la notification existante (administrateur)
        $notifyEmail = config('site.notify_email');

        if (empty($notifyEmail)) {
            Log::warning('Notification nouvelle demande : aucun e-mail de notification configuré dans config(site.notify_email).');
        } else {
            try {
                Notification::route('mail', $notifyEmail)
                    ->notify(new NouvelleDemande($demande));
            } catch (Throwable $e) {
                Log::error('Échec de l’envoi de l’e-mail de notification pour la demande #' . $demande->id . ' : ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }

        // 3) Envoie l'e-mail de confirmation au visiteur
        if (!empty($demande->email)) {
            try {
                Notification::route('mail', $demande->email)
                    ->notify(new ConfirmationDemande($demande));
            } catch (Throwable $e) {
                Log::error('Échec de l’envoi de l’e-mail de confirmation au visiteur pour la demande #' . $demande->id . ' : ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }

        return response()->json(['ok' => true], 201);
    }
}

