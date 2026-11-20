<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Commentaire d'un article : enregistré « En attente », publié après relecture dans l'admin.
 * Seuls un prénom et le message sont demandés. Champ « website » = piège à robots.
 */
class CommentaireController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $article = ArticleBlog::publies()->where('slug', $slug)->firstOrFail();
        $retour = route('blog.article', $article->slug).'#commentaires';

        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->filled('website')) {
            return redirect()->to($retour)->with('commentaire_envoye', true);
        }

        $data = $request->validate([
            'nom' => ['required', 'string', 'min:2', 'max:80'],
            'message' => ['required', 'string', 'min:3', 'max:1500'],
        ], [
            'nom.required' => 'Indiquez votre prénom ou votre nom.',
            'nom.min' => 'Indiquez votre prénom ou votre nom.',
            'nom.max' => 'Votre nom ne peut pas dépasser 80 caractères.',
            'message.required' => 'Écrivez votre commentaire.',
            'message.min' => 'Écrivez votre commentaire en quelques mots.',
            'message.max' => 'Votre commentaire ne peut pas dépasser 1500 caractères.',
        ]);

        $article->commentaires()->create([
            'nom' => trim($data['nom']),
            'message' => trim($data['message']),
            'statut' => 'En attente',
        ]);

        return redirect()->to($retour)->with('commentaire_envoye', true);
    }
}
