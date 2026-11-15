@props(['title', 'open' => false, 'as' => 'h3'])
@php $uid = 'acc-'.\Illuminate\Support\Str::random(6); @endphp
<div x-data="{ open: @js((bool) $open) }" {{ $attributes->class('border-b border-line') }}>
  <{{ $as }} class="text-lg md:text-xl">
    <button
      type="button"
      id="{{ $uid }}-btn"
      class="group flex w-full items-center justify-between gap-4 rounded-xl py-5 text-left font-heading font-semibold text-ink"
      @click="open = !open"
      :aria-expanded="open.toString()"
      aria-controls="{{ $uid }}-panel"
    >
      <span>{{ $title }}</span>
      <span
        class="grid size-8 shrink-0 place-items-center rounded-full border border-line bg-surface text-muted transition duration-200 ease-out group-hover:text-ink"
        :class="open && 'rotate-180 border-primary/30 bg-primary/10 text-primary group-hover:text-primary'"
        aria-hidden="true"
      >
        <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 9l6 6 6-6"/>
        </svg>
      </span>
    </button>
  </{{ $as }}>

  <div
    id="{{ $uid }}-panel"
    role="region"
    aria-labelledby="{{ $uid }}-btn"
    x-show="open"
    x-collapse
    @unless ($open) x-cloak @endunless
  >
    <div class="pb-5 text-base text-muted">
      {{ $slot }}
    </div>
  </div>
</div>
