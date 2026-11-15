@props(['title' => null, 'as' => 'h3'])
<div {{ $attributes->class('rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:shadow-float md:p-8') }}>
  @isset($icon)
    <span class="mb-5 grid size-12 place-items-center rounded-full bg-primary/10 text-primary [&>svg]:size-6" aria-hidden="true">
      {{ $icon }}
    </span>
  @endisset

  @if ($title)
    <{{ $as }} class="text-xl md:text-2xl">{{ $title }}</{{ $as }}>
  @endif

  @if ($slot->isNotEmpty())
    <div @class(['text-base text-muted', 'mt-3' => $title])>
      {{ $slot }}
    </div>
  @endif
</div>
