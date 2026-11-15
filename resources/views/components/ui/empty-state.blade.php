{{-- État vide : quand une liste n'a encore aucun contenu publié. Slot : action (bouton). --}}
@props(['title', 'text' => null])
<div {{ $attributes->class('mx-auto flex max-w-[560px] flex-col items-center rounded-[24px] border border-dashed border-line bg-surface px-6 py-14 text-center md:px-10') }}>
  <span class="grid size-12 place-items-center rounded-full bg-white text-muted ring-1 ring-line" aria-hidden="true">
    <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  </span>
  <p class="mt-5 font-heading text-xl font-bold text-ink">{{ $title }}</p>
  @if ($text)
    <p class="mt-2 text-base text-muted">{{ $text }}</p>
  @endif
  @if ($slot->isNotEmpty())
    <div class="mt-7">{{ $slot }}</div>
  @endif
</div>
