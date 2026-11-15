@props(['bg' => 'white', 'width' => 'default'])
@php
$bgs = [
  'white'   => 'bg-white text-ink',
  'surface' => 'bg-surface text-ink',
  'ink'     => 'bg-ink-soft text-white',
];
$widths = [
  'default' => 'max-w-[1200px]',
  'wide'    => 'max-w-[1400px]',
];
@endphp
<section {{ $attributes->class(['py-16 md:py-24', $bgs[$bg] ?? $bgs['white']]) }}>
  <div class="mx-auto w-full {{ $widths[$width] ?? $widths['default'] }} px-6 md:px-12">
    {{ $slot }}
  </div>
</section>
