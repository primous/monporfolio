@props(['variant' => 'primary', 'href' => null, 'icon' => true, 'size' => 'default'])
@php
$compact = $size === 'compact';
// La taille du texte dépend de size : jamais text-base et text-sm ensemble.
$base = 'group inline-flex items-center gap-2.5 rounded-full font-body font-semibold transition duration-200 ease-out disabled:pointer-events-none disabled:opacity-50 '
  . ($compact ? 'text-sm' : 'text-base');
$variants = $compact
  ? [
      'primary'   => 'bg-primary text-white hover:bg-primary-hover px-4 py-2',
      'secondary' => 'bg-ink text-white hover:bg-ink-soft px-4 py-2',
      'ghost'     => 'bg-surface text-ink border border-line hover:bg-black/5 px-4 py-2',
    ]
  : [
      'primary'   => 'bg-primary text-white hover:bg-primary-hover p-1 pr-5',
      'secondary' => 'bg-ink text-white hover:bg-ink-soft px-6 py-3.5',
      'ghost'     => 'bg-surface text-ink border border-line hover:bg-black/5 px-6 py-3.5',
    ];
$tag = $href ? 'a' : 'button';
@endphp
<{{ $tag }} {{ $attributes->class([$base, $variants[$variant]])->merge($href ? ['href' => $href] : ['type' => 'button']) }}>
  @if ($variant === 'primary' && $icon && ! $compact)
    <span class="grid size-11 shrink-0 place-items-center rounded-full bg-white text-primary">
      <svg viewBox="0 0 44 44" class="size-11 transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M17 27.5L26.5 18M26.5 18V26M26.5 18H18.5"/>
      </svg>
    </span>
  @endif
  <span>{{ $slot }}</span>
</{{ $tag }}>
