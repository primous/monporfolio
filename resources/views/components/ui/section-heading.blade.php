@props(['badge' => null, 'title', 'subtitle' => null, 'align' => 'center', 'as' => 'h2', 'display' => false, 'dark' => false])
@php $center = $align === 'center'; @endphp
<div {{ $attributes->class(['flex flex-col gap-4', 'items-center text-center mx-auto' => $center, 'items-start text-left' => ! $center]) }}>
  @if ($badge)
    <x-ui.badge :class="$dark ? '!border-white/15 !bg-white/10 !text-white' : ''">{{ $badge }}</x-ui.badge>
  @endif

  <{{ $as }} @class([
    'max-w-[839px]',
    'display text-[34px] md:text-[48px] lg:text-[60px]' => $display,
    'text-[28px] md:text-[36px] lg:text-[44px]' => ! $display,
    'text-white' => $dark,
  ])>{{ $title }}</{{ $as }}>

  @if ($subtitle)
    <p @class(['max-w-[619px] text-base md:text-lg', 'text-muted' => ! $dark, 'text-white/70' => $dark])>{{ $subtitle }}</p>
  @endif
</div>
