@php
$url = fn (?string $route) => $route && Route::has($route) ? route($route) : '#';

// Liens résolus (route, sinon section de l'accueil) ; ceux sans destination sont masqués.
$links = \App\Support\SiteNav::links();

$phone = (string) config('site.phone');
$telUrl = 'tel:'.preg_replace('/[^0-9+]/', '', $phone);
$waUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', (string) config('site.whatsapp'))
    .'?text='.urlencode((string) config('site.whatsapp_message'));
$formUrl = $url(config('site.form_route'));
$homeUrl = $url('home');
@endphp

<header
  x-data="{ open: false }"
  x-trap.noscroll="open"
  @keydown.escape.window="if (open) { open = false; $refs.toggle.focus() }"
  class="sticky top-0 z-[100] border-b border-line bg-white/90 backdrop-blur"
>
  <nav class="mx-auto flex h-[72px] w-full max-w-[1200px] items-center justify-between gap-4 px-6 md:px-12" aria-label="Navigation principale">
    <a href="{{ $homeUrl }}" class="flex shrink-0 items-center rounded-lg">
      <img src="{{ asset('images/brand/logo.png') }}" alt="Hounkpatin Primous" width="1000" height="305" class="h-10 w-auto md:h-11">
    </a>

    {{-- Liens centraux (≥ md) --}}
    <ul class="hidden items-center gap-1.5 md:flex">
      @foreach ($links as $link)
        <li>
          <a
            href="{{ $link['href'] }}"
            @if ($link['active']) aria-current="page" @endif
            @class([
              'rounded-full px-4 py-1 text-sm font-medium transition duration-200 ease-out',
              'bg-primary/10 text-primary' => $link['active'],
              'bg-surface text-ink hover:bg-black/10' => ! $link['active'],
            ])
          >{{ $link['label'] }}</a>
        </li>
      @endforeach
    </ul>

    <div class="flex items-center gap-2">
      {{-- Droite (≥ lg) : appel, WhatsApp, CTA --}}
      <div class="hidden items-center gap-2 lg:flex">
        <a href="{{ $telUrl }}" aria-label="Appeler" class="grid size-11 place-items-center rounded-full border border-line bg-surface text-ink transition duration-200 hover:text-primary">
          <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
          </svg>
        </a>
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="grid size-11 place-items-center rounded-full border border-line bg-surface text-ink transition duration-200 hover:text-primary">
          <x-ui.icon-whatsapp class="size-5" />
        </a>
        <x-ui.button :href="$formUrl" class="ml-1 whitespace-nowrap">Démarrer mon projet</x-ui.button>
      </div>

      {{-- < lg : CTA compact (le conteneur porte le masquage, le bouton a inline-flex en base) --}}
      <div class="hidden md:block lg:hidden">
        <x-ui.button :href="$formUrl" size="compact" class="whitespace-nowrap">Démarrer mon projet</x-ui.button>
      </div>

      {{-- Bouton menu (< md) --}}
      <button
        type="button"
        x-ref="toggle"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-expanded="false"
        aria-controls="menu-mobile"
        aria-label="Menu"
        class="grid size-11 place-items-center rounded-full border border-line bg-surface text-ink transition duration-200 hover:text-primary md:hidden"
        :class="open && 'border-primary/30 bg-primary/10 text-primary'"
      >
        <svg x-show="!open" viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        <svg x-show="open" x-cloak viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
    </div>
  </nav>

  {{-- Tiroir mobile. absolute (et non fixed) : backdrop-blur du header crée un bloc conteneur. --}}
  <div
    id="menu-mobile"
    x-show="open"
    x-cloak
    x-transition:enter="transition duration-300 ease-out"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition duration-200 ease-out"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="absolute inset-x-0 top-full h-[calc(100dvh-72px)] overflow-y-auto border-t border-line bg-white px-6 pb-10 pt-6 md:hidden"
  >
    <ul class="flex flex-col gap-1">
      @foreach ($links as $link)
        <li>
          <a
            href="{{ $link['href'] }}"
            @click="open = false"
            @if ($link['active']) aria-current="page" @endif
            @class([
              'block rounded-2xl px-4 py-3 font-heading text-2xl font-semibold tracking-tight transition duration-200',
              'bg-primary/10 text-primary' => $link['active'],
              'text-ink hover:bg-surface' => ! $link['active'],
            ])
          >{{ $link['label'] }}</a>
        </li>
      @endforeach
    </ul>

    <div class="mt-8 flex flex-col gap-3">
      <x-ui.button variant="ghost" :href="$telUrl" class="w-full justify-center">
        <span class="inline-flex items-center gap-2">
          <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
          </svg>
          Appeler
        </span>
      </x-ui.button>
      <x-ui.button variant="ghost" :href="$waUrl" target="_blank" rel="noopener" class="w-full justify-center">
        <span class="inline-flex items-center gap-2">
          <x-ui.icon-whatsapp class="size-5" />
          WhatsApp
        </span>
      </x-ui.button>
    </div>
  </div>
</header>
