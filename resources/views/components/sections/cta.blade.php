@php
$formRoute = config('site.form_route');
$formUrl = Route::has($formRoute) ? route($formRoute) : '#';
@endphp

{{-- Bande noire pleine largeur qui se fond dans le footer : une seule zone sombre en fin de page --}}
<x-layout.section bg="ink">
  <div class="reveal mx-auto flex max-w-[839px] flex-col items-center gap-6 text-center" style="--i:0">
    <h2 class="display text-[34px] text-white md:text-[52px] lg:text-[64px]">Prêt à ne plus répéter les mêmes réponses&nbsp;?</h2>
    <p class="max-w-[619px] text-base text-white/80 md:text-lg">
      Cinq questions sur votre activité, et je vous envoie une proposition adaptée. Premier échange gratuit et sans engagement.
    </p>
    <x-ui.button :href="$formUrl" class="mt-2">Démarrer mon projet</x-ui.button>
  </div>
  {{-- Filet de séparation avec le footer ; la marge négative annule le padding bas de la section --}}
  <div class="-mb-16 mt-16 border-t border-white/10 md:-mb-24 md:mt-24" aria-hidden="true"></div>
</x-layout.section>
