<div {{ $attributes->class('flex items-center gap-3') }}>
  <svg viewBox="0 0 24 24" class="size-6 shrink-0" aria-hidden="true">
    <circle cx="12" cy="12" r="12" class="fill-primary"/>
    <path d="M7.5 12.5l3 3 6-6.5" fill="none" class="stroke-white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <span class="text-[15px] font-medium text-ink">{{ $slot }}</span>
</div>
