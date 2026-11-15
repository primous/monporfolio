@props(['variant' => 'default'])
@php
$variants = [
  'default' => 'bg-surface text-ink border border-line',
  'active'  => 'bg-primary/10 text-primary',
];
@endphp
<span {{ $attributes->class(['inline-flex items-center gap-2 rounded-full px-3 py-1 font-mono text-xs font-medium uppercase tracking-[0.06em]', $variants[$variant] ?? $variants['default']]) }}>
  {{ $slot }}
</span>
