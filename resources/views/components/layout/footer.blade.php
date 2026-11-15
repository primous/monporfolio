@php
$url = fn (?string $route) => $route && Route::has($route) ? route($route) : '#';

// Liens résolus (route, sinon section de l'accueil) ; ceux sans destination sont masqués.
$links = \App\Support\SiteNav::links();

$telUrl = 'tel:'.preg_replace('/[^0-9+]/', '', (string) config('site.phone'));
$waUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', (string) config('site.whatsapp'))
    .'?text='.urlencode((string) config('site.whatsapp_message'));
$formUrl = $url(config('site.form_route'));

$link = 'rounded text-white/70 transition duration-200 hover:text-white';
@endphp

<footer class="bg-ink-soft text-white"
  x-data="{
    open: false, message: '', website: '', sending: false, sent: false, error: '',
    show() { this.open = true; this.error = ''; this.$nextTick(() => this.$refs.message?.focus()); },
    close() { this.open = false; if (this.sent) { this.sent = false; this.message = ''; } if (location.hash === '#suggestion') history.replaceState(null, '', location.pathname + location.search); },
    async send() {
      this.error = '';
      if (this.message.trim().length < 5) { this.error = 'Écrivez votre suggestion en quelques mots.'; return; }
      this.sending = true;
      try {
        const res = await fetch('{{ route('suggestion.store') }}', {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
          body: JSON.stringify({ message: this.message, website: this.website, page: location.pathname }),
        });
        if (res.status === 201) { this.sent = true; }
        else if (res.status === 422) { const d = await res.json(); this.error = d.errors?.message?.[0] || 'Vérifiez votre message.'; }
        else if (res.status === 429) { this.error = 'Trop d’envois d’un coup. Réessayez dans quelques minutes.'; }
        else { this.error = 'Votre suggestion n’a pas pu être envoyée. Réessayez dans un instant.'; }
      } catch (e) { this.error = 'Votre suggestion n’a pas pu être envoyée. Vérifiez votre connexion.'; }
      finally { this.sending = false; }
    },
  }"
  x-init="if (location.hash === '#suggestion') show(); window.addEventListener('hashchange', () => { if (location.hash === '#suggestion') show(); })"
>
  <div class="mx-auto w-full max-w-[1200px] px-6 py-16 md:px-12">
    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
      {{-- Bloc 1 --}}
      <div class="flex flex-col gap-3">
        {{-- Logo en version claire (texte blanc) pour le fond noir --}}
        <a href="{{ $url('home') }}" class="inline-flex w-fit rounded-lg">
          <img src="{{ asset('images/brand/logo-light.png') }}" alt="Hounkpatin Primous — accueil" width="1000" height="305" loading="lazy" class="h-12 w-auto md:h-14">
        </a>
        <p class="text-sm text-white/70">Un site clair qui explique votre activité une seule fois.</p>
      </div>

      {{-- Bloc 2 --}}
      <nav aria-labelledby="footer-explorer">
        <h2 id="footer-explorer" class="font-mono text-xs font-medium uppercase tracking-[0.08em] text-white/50">Explorer</h2>
        <ul class="mt-4 flex flex-col gap-2.5 text-sm">
          @foreach ($links as $item)
            <li><a href="{{ $item['href'] }}" class="{{ $link }}">{{ $item['label'] }}</a></li>
          @endforeach
        </ul>
      </nav>

      {{-- Bloc 3 --}}
      <div>
        <h2 class="font-mono text-xs font-medium uppercase tracking-[0.08em] text-white/50">Me joindre</h2>
        <ul class="mt-4 flex flex-col gap-2.5 text-sm">
          <li>
            <a href="{{ $telUrl }}" class="{{ $link }} inline-flex items-center gap-2">
              <svg viewBox="0 0 24 24" class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
              </svg>
              Appeler
            </a>
          </li>
          <li>
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="{{ $link }} inline-flex items-center gap-2">
              <x-ui.icon-whatsapp class="size-4 shrink-0" />
              WhatsApp
            </a>
          </li>
          <li class="text-white/70">
            Ou&nbsp;: <a href="{{ $formUrl }}" class="rounded text-white underline decoration-primary underline-offset-4 transition duration-200 hover:text-white">Démarrer mon projet</a>
          </li>
        </ul>
      </div>

      {{-- Bloc 4 --}}
      <div>
        <h2 class="font-mono text-xs font-medium uppercase tracking-[0.08em] text-white/50">Votre avis compte</h2>
        <p class="mt-4 text-sm text-white/70">Une idée pour améliorer ce site&nbsp;?</p>
        <a href="#suggestion" @click.prevent="show()" aria-haspopup="dialog" class="{{ $link }} mt-2 inline-block text-sm underline decoration-primary underline-offset-4">Envoyer une suggestion anonyme (sans nom, sans coordonnées)</a>
      </div>
    </div>

    <div class="mt-12 flex flex-col gap-3 border-t border-white/10 pt-6 text-sm text-white/50 md:flex-row md:items-center md:justify-between">
      <p class="flex flex-wrap items-center gap-2">
        <a href="{{ $url('mentions-legales') }}" class="rounded transition duration-200 hover:text-white">Mentions légales</a>
        <span aria-hidden="true">·</span>
        <a href="{{ $url('politique-confidentialite') }}" class="rounded transition duration-200 hover:text-white">Politique de confidentialité</a>
      </p>
      <p>© {{ date('Y') }} Hounkpatin Primous. Tous droits réservés.</p>
    </div>
  </div>

  {{-- Fenêtre « suggestion anonyme » --}}
  <div
    x-show="open" x-cloak
    class="fixed inset-0 z-[200] flex items-end justify-center p-0 sm:items-center sm:p-6"
    role="dialog" aria-modal="true" aria-labelledby="suggestion-titre"
    @keydown.escape.window="open && close()"
  >
    <div x-show="open" x-transition.opacity.duration.200ms class="absolute inset-0 bg-ink/60 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>

    <div
      x-show="open" x-trap.noscroll="open"
      x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition duration-200 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-y-6 opacity-0"
      class="relative w-full max-w-[520px] rounded-t-[28px] bg-white p-6 text-ink shadow-float sm:rounded-[28px] md:p-8"
    >
      <button type="button" @click="close()" class="absolute right-4 top-4 grid size-10 place-items-center rounded-full bg-surface text-ink ring-1 ring-line transition duration-200 hover:text-primary" aria-label="Fermer">
        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>

      {{-- Formulaire --}}
      <div x-show="!sent">
        <p class="font-mono text-xs font-medium uppercase tracking-[0.06em] text-muted">Suggestion anonyme</p>
        <h2 id="suggestion-titre" class="display mt-3 pr-10 text-2xl md:text-[28px]">Une idée pour améliorer ce site&nbsp;?</h2>
        <p class="mt-3 text-sm text-muted">Je ne demande ni nom ni e-mail, et je n’enregistre pas votre adresse IP. Seul votre message me parvient.</p>

        <form class="mt-6" @submit.prevent="send()" novalidate>
          <label for="suggestion-message" class="sr-only">Votre suggestion</label>
          <textarea id="suggestion-message" x-ref="message" x-model="message" rows="5" maxlength="2000"
            placeholder="Ce qui vous a manqué, ce qui n’était pas clair, une idée…"
            class="block w-full rounded-xl border bg-white px-4 py-3 text-base text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
            :class="error ? 'border-ink' : 'border-line'"
            :aria-invalid="error ? 'true' : 'false'" aria-describedby="suggestion-aide"></textarea>
          {{-- Piège à robots (invisible) --}}
          <input type="text" x-model="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 opacity-0">

          <div class="mt-2 flex items-start justify-between gap-4 text-sm" id="suggestion-aide">
            <p x-show="error" class="flex items-center gap-2 font-medium text-ink" role="alert">
              <svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
              <span x-text="error"></span>
            </p>
            <p class="ml-auto shrink-0 font-mono text-xs text-muted"><span x-text="message.length">0</span> / 2000</p>
          </div>

          <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-ui.button variant="ghost" :icon="false" @click="close()" class="justify-center">Annuler</x-ui.button>
            <x-ui.button type="submit" ::disabled="sending" class="justify-center">
              <span x-text="sending ? 'Envoi…' : 'Envoyer ma suggestion'">Envoyer ma suggestion</span>
            </x-ui.button>
          </div>
        </form>
      </div>

      {{-- Confirmation (affichée seulement après la réponse 201) --}}
      <div x-show="sent" x-cloak class="py-6 text-center" role="status">
        <span class="mx-auto grid size-14 place-items-center rounded-full bg-primary/10 text-primary" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="size-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
        </span>
        <p class="display mt-5 text-2xl">Merci, votre suggestion est bien arrivée.</p>
        <p class="mt-2 text-sm text-muted">Je la lirai avec attention.</p>
        <div class="mt-7 flex justify-center">
          <x-ui.button variant="ghost" :icon="false" @click="close()">Fermer</x-ui.button>
        </div>
      </div>
    </div>
  </div>
</footer>
