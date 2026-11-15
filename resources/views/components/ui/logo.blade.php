@props([
    'variant' => 'default', // 'default' (fond clair, texte noir) ou 'light' / 'inverted' (fond sombre, texte blanc)
    'size' => 'default',    // 'sm' (28px), 'default' (38px), 'lg' (48px)
    'withLink' => true,
    'href' => url('/'),
    'showText' => false, // le nouveau logo contient déjà le nom
])

@php
    $isLight = $variant === 'light' || $variant === 'inverted';
    $textColor = $isLight ? 'text-white' : 'text-ink';
    $logoSrc = $isLight ? asset('images/brand/logo-light.png') : asset('images/brand/logo.png');
    
    $logoHeight = match($size) {
        'sm' => 'h-7',
        'lg' => 'h-12',
        default => 'h-9 sm:h-10',
    };

    $textSize = match($size) {
        'sm' => 'text-lg',
        'lg' => 'text-2xl',
        default => 'text-xl sm:text-[23px]',
    };

    $gap = match($size) {
        'sm' => 'gap-2',
        'lg' => 'gap-3.5',
        default => 'gap-2.5 sm:gap-3',
    };
@endphp

@if($withLink)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center ' . $gap . ' group shrink-0 select-none']) }}>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center ' . $gap . ' shrink-0 select-none']) }}>
@endif

    <!-- Véritable monogramme HPH détouré et recadré en haute résolution -->
    <img 
        src="{{ $logoSrc }}" 
        alt="Hounkpatin Primous" 
        class="{{ $logoHeight }} w-auto object-contain transition-transform duration-200 group-hover:scale-105"
        loading="eager"
    />

    @if($showText)
        <!-- Lettrage Primous moderne et harmonieux (style ClassiAds) -->
        <span class="font-heading font-extrabold {{ $textSize }} {{ $textColor }} tracking-tight leading-none">
            Primous
        </span>
    @endif

@if($withLink)
    </a>
@else
    </div>
@endif
