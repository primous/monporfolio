<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Résout les liens de navigation de config('site.nav') pour la navbar et le footer.
 * Un lien sans destination réelle (ni route, ni section de l'accueil) n'est pas affiché.
 */
class SiteNav
{
    /**
     * @return array<int, array{label: string, href: string, active: bool}>
     */
    public static function links(): array
    {
        $links = [];

        foreach (config('site.nav', []) as $item) {
            if (! empty($item['requires']) && empty(config('site.'.$item['requires']))) {
                continue;
            }

            $route = $item['route'] ?? null;

            if ($route && Route::has($route)) {
                $links[] = ['label' => $item['label'], 'href' => route($route), 'active' => request()->routeIs($route)];
            } elseif (! empty($item['anchor']) && Route::has('home')) {
                $links[] = ['label' => $item['label'], 'href' => route('home').'#'.$item['anchor'], 'active' => false];
            }
        }

        return $links;
    }
}
