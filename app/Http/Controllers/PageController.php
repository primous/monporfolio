<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Avis;
use App\Models\Projet;
use App\Models\Service;
use Illuminate\Contracts\View\View;

/** Pages publiques alimentées par l'admin (services, projets, avis, blog) + page Parcours. */
class PageController extends Controller
{
    public function services(): View
    {
        return view('pages.services', [
            'services' => Service::visibles()->get(),
        ]);
    }

    public function projets(): View
    {
        return view('pages.projets', [
            'projets' => Projet::visibles()->get(),
        ]);
    }

    public function parcours(): View
    {
        return view('pages.parcours', [
            'projets' => Projet::visibles()->take(3)->get(),
        ]);
    }

    public function avis(): View
    {
        return view('pages.avis', [
            'avis' => Avis::publies()->get(),
        ]);
    }

    public function blog(): View
    {
        return view('pages.blog', [
            'articles' => ArticleBlog::publies()->paginate(9),
        ]);
    }

    public function article(string $slug): View
    {
        // Seuls les articles publiés (date passée) sont accessibles ; sinon 404.
        $article = ArticleBlog::publies()->where('slug', $slug)->firstOrFail();

        return view('pages.article', [
            'article' => $article,
            'autres' => ArticleBlog::publies()->whereKeyNot($article->getKey())->take(2)->get(),
        ]);
    }
}
