<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'max' => 'Le champ :attribute est trop long.',
            'message.min' => 'Décrivez votre projet en quelques mots (10 caractères minimum).',
        ], [
            'name' => 'nom',
            'email' => 'e-mail',
            'message' => 'projet',
        ]);

        ContactMessage::create($data);

        return redirect()->to(route('home').'#contact')->with('contact_sent', true);
    }
}
