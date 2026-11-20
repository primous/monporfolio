<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Avis;
use App\Models\CategorieBlog;
use App\Models\CommentaireBlog;
use Illuminate\Http\Request;
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

    public function blog(Request $request): View
    {
        // Catégories qui ont au moins un article publié (avec leur nombre d'articles).
        $categories = CategorieBlog::query()
            ->withCount(['articles' => fn ($q) => $q->publies()])
            ->orderBy('ordre_affichage')->orderBy('nom')
            ->get()
            ->where('articles_count', '>', 0)
            ->values();

        $active = $categories->firstWhere('slug', $request->query('categorie'));

        $articles = ArticleBlog::publies()
            ->with('categorie')
            ->withCount(['commentaires as commentaires_publies_count' => fn ($q) => $q->where('statut', 'Publié')])
            ->when($active, fn ($q) => $q->where('categorie_blog_id', $active->id))
            ->paginate(9)
            ->withQueryString();

        // Statistiques réelles du blog (articles publiés uniquement).
        $publies = ArticleBlog::publies();
        $stats = [
            'articles' => (clone $publies)->count(),
            'vues' => (int) (clone $publies)->sum('vues'),
            'commentaires' => CommentaireBlog::where('statut', 'Publié')
                ->whereIn('article_blog_id', (clone $publies)->select('id'))->count(),
        ];

        return view('pages.blog', compact('articles', 'categories', 'active', 'stats'));
    }

    public function article(Request $request, string $slug): View
    {
        // Seuls les articles publiés (date passée) sont accessibles ; sinon 404.
        $article = ArticleBlog::publies()->with('categorie')->where('slug', $slug)->firstOrFail();

        // Une lecture par visiteur et par session ; les robots ne comptent pas.
        $dejaVus = $request->session()->get('blog_vus', []);
        $robot = (bool) preg_match('/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|preview|curl|wget|python|headless/i', (string) $request->userAgent());
        if (! $robot && ! in_array($article->id, $dejaVus, true)) {
            $article->compterUneVue();
            $request->session()->put('blog_vus', [...$dejaVus, $article->id]);
        }

        return view('pages.article', [
            'article' => $article,
            'commentaires' => $article->commentairesPublies()->get(),
            'autres' => ArticleBlog::publies()->with('categorie')->whereKeyNot($article->getKey())
                // Même catégorie d'abord, puis les plus récents.
                ->when($article->categorie_blog_id, fn ($q) => $q->reorder()
                    ->orderByRaw('categorie_blog_id = ? desc', [$article->categorie_blog_id])
                    ->orderByDesc('date_publication'))
                ->take(2)->get(),
        ]);
    }
}
