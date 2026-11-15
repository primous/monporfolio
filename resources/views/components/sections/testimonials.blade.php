@php
// Avis clients publiés dans l'admin (Contenu du site → Avis clients). Aucun avis publié => section absente.
$testimonials = \App\Models\Avis::publies()->take(3)->get()
    ->map(fn ($a) => ['quote' => $a->contenu, 'name' => $a->nom, 'role' => $a->activite])->all();
@endphp

@if (! empty($testimonials))
<x-layout.section bg="surface" id="avis">
  <div class="reveal" style="--i:0">
    <x-ui.section-heading badge="Avis" title="Ce qu’en disent mes clients" />
  </div>

  <ul class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($testimonials as $n => $t)
      <li class="reveal" style="--i:{{ min($n % 3 + 1, 3) }}">
        <figure class="flex h-full flex-col gap-5 rounded-[24px] border border-line bg-white p-6 shadow-card md:p-8">
          <svg viewBox="0 0 24 24" class="size-8 text-primary" fill="currentColor" aria-hidden="true">
            <path d="M9.5 6C6.5 7 4.5 9.6 4.5 13v5h5.5v-6H7.3c.2-1.9 1.4-3.3 3-4l-.8-2zm9 0c-3 1-5 3.6-5 7v5H19v-6h-2.7c.2-1.9 1.4-3.3 3-4l-.8-2z"/>
          </svg>
          <blockquote class="flex-1 text-base text-ink">« {{ $t['quote'] }} »</blockquote>
          <figcaption class="text-sm">
            <span class="block font-semibold text-ink">{{ $t['name'] }}</span>
            @if (! empty($t['role']))
              <span class="text-muted">{{ $t['role'] }}</span>
            @endif
          </figcaption>
        </figure>
      </li>
    @endforeach
  </ul>
</x-layout.section>
@endif
