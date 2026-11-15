@props(['src', 'alt', 'width' => null, 'height' => null, 'lazy' => true, 'imgClass' => ''])
@php
// width + height fournis : image entière, sans recadrage. Sinon : remplissage recadré (comportement initial).
$natural = $width && $height;
@endphp
<div {{ $attributes->class('overflow-hidden rounded-[24px] border-[4px] border-white shadow-card ring-1 ring-line md:border-[6px]') }}>
  <img
    src="{{ $src }}"
    alt="{{ $alt }}"
    @if ($natural) width="{{ $width }}" height="{{ $height }}" @endif
    loading="{{ $lazy ? 'lazy' : 'eager' }}"
    decoding="async"
    @class([
      'block h-auto w-full' => $natural,
      'h-full w-full object-cover' => ! $natural,
      $imgClass,
    ])
  >
</div>
