<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentaireRequest;
use App\Models\ArticleBlog;
use Illuminate\Http\RedirectResponse;

/**
 * Commentaire d'un article : enregistré « En attente », publié après relecture dans l'admin.
 * Seuls un prénom et le message sont demandés. Validation : StoreCommentaireRequest.
 */
class CommentaireController extends Controller
{
    public function store(StoreCommentaireRequest $request, string $slug): RedirectResponse
    {
        $article = ArticleBlog::publies()->where('slug', $slug)->firstOrFail();
        $retour = route('blog.article', $article->slug).'#commentaires';

        // Piège à robots rempli : on feint le succès sans rien enregistrer.
        if ($request->estUnRobot()) {
            return redirect()->to($retour)->with('commentaire_envoye', true);
        }

        $data = $request->validated();

        $article->commentaires()->create([
            'nom' => trim($data['nom']),
            'message' => trim($data['message']),
            'statut' => 'En attente',
        ]);

        return redirect()->to($retour)->with('commentaire_envoye', true);
    }
}
