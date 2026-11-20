<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

/** Ancien formulaire de contact (conservé). Validation : StoreContactRequest. */
class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $retour = route('home').'#contact';

        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->estUnRobot()) {
            return redirect()->to($retour)->with('contact_sent', true);
        }

        ContactMessage::create($request->validated());

        return redirect()->to($retour)->with('contact_sent', true);
    }
}
