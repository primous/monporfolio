{{-- Cadre de navigateur pour présenter une capture entière (aucun recadrage). --}}
@props(['src', 'alt', 'width', 'height', 'url' => null, 'lazy' => true])
<div {{ $attributes->class('overflow-hidden rounded-[18px] border border-line bg-white shadow-card') }}>
  <div class="flex items-center gap-3 border-b border-line bg-surface px-4 py-2.5" aria-hidden="true">
    <span class="flex gap-1.5">
      <span class="size-2.5 rounded-full bg-line"></span>
      <span class="size-2.5 rounded-full bg-line"></span>
      <span class="size-2.5 rounded-full bg-line"></span>
    </span>
    @if ($url)
      <span class="mx-auto truncate rounded-full bg-white px-3 py-0.5 text-[11px] text-muted ring-1 ring-line">{{ $url }}</span>
      <span class="w-[42px]"></span>
    @endif
  </div>
  <img src="{{ $src }}" alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}"
       loading="{{ $lazy ? 'lazy' : 'eager' }}" decoding="async" class="block h-auto w-full">
</div>
