{{--
  Motif de marque : les trois barres montantes du logo (rose, rose, rouge). Toujours décoratif.
  (Le composant garde son nom « diamond » : il remplaçait le losange de l'ancien logo.)
--}}
@props(['size' => 'sm', 'tone' => 'primary'])
@php
// [largeur d'une barre, hauteurs des 3 barres] en px
$sizes = [
  'xs' => [2, [4, 6, 8]],
  'sm' => [2.5, [5, 7.5, 10]],
  'md' => [3, [6, 9.5, 13]],
  'lg' => [4, [8, 12.5, 17]],
];
[$w, $hs] = $sizes[$size] ?? $sizes['sm'];
$tones = [
  'primary' => ['bg-primary-soft', 'bg-primary-soft', 'bg-primary'],
  'white'   => ['bg-white/50', 'bg-white/70', 'bg-white'],
  'ink'     => ['bg-ink/40', 'bg-ink/60', 'bg-ink'],
  'line'    => ['bg-line', 'bg-line', 'bg-line'],
];
$colors = $tones[$tone] ?? $tones['primary'];
@endphp
<span {{ $attributes->class('inline-flex shrink-0 items-end') }} style="gap: {{ $w / 2 }}px" aria-hidden="true">
  @foreach ($hs as $n => $h)
    <span class="block rounded-[1px] {{ $colors[$n] }}" style="width: {{ $w }}px; height: {{ $h }}px"></span>
  @endforeach
</span>
