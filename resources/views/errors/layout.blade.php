{{--
  Pages d'erreur du site (403, 404, 419, 429, 500, 503) : aux couleurs du site, jamais la page
  par défaut de Laravel (qui signale le framework aux robots), jamais de détail technique.
  Volontairement autonome : ni session, ni base de données, ni Livewire (elle doit s'afficher
  même quand le reste est en panne).
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('titre') | Hounkpatin Primous</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
  @vite(['resources/css/app.css'])
</head>
<body class="bg-white font-body text-ink antialiased">
  <main class="mx-auto flex min-h-dvh w-full max-w-[720px] flex-col items-center justify-center px-6 py-16 text-center">
    <a href="{{ url('/') }}" class="mb-12">
      <img src="{{ asset('images/brand/logo-400.png') }}" alt="Hounkpatin Primous" width="400" height="122" class="h-10 w-auto">
    </a>

    <p class="num-outline text-7xl font-bold leading-none text-primary md:text-8xl">@yield('code')</p>
    <h1 class="display mt-6 text-3xl font-semibold tracking-tight md:text-4xl">@yield('titre')</h1>
    <p class="mt-4 max-w-[52ch] text-lg text-muted">@yield('message')</p>

    <div class="mt-10 flex flex-col items-center gap-3 sm:flex-row">
      <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 font-semibold text-white transition duration-200 hover:bg-primary-hover">Retour à l’accueil</a>
      @hasSection('action')
        @yield('action')
      @endif
    </div>
  </main>
</body>
</html>
