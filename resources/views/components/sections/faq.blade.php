@php
$faqs = [
    ['Combien de temps faut-il pour créer un site ?', 'Comptez en général deux à quatre semaines pour un site vitrine, selon le nombre de pages et la disponibilité de vos contenus.'],
    ['Dois-je fournir les textes et les photos ?', 'Vous apportez ce que vous avez. Je vous aide à structurer les textes et je peux utiliser des visuels adaptés si besoin.'],
    ['Pourrai-je modifier mon site moi-même ?', 'Oui. Un espace d’administration simple vous permet de mettre à jour vos contenus sans toucher au code.'],
    ['Le site sera-t-il adapté aux mobiles ?', 'Toujours. Chaque page est conçue d’abord pour le téléphone, puis pour la tablette et l’ordinateur.'],
];
@endphp

<x-layout.section bg="white" id="faq">
  <div class="grid gap-10 lg:grid-cols-[1fr_1.4fr] lg:gap-16">
    <div class="reveal" style="--i:0" x-intersect.once="$el.classList.add('is-visible')">
      <x-ui.section-heading
        align="left"
        badge="Questions fréquentes"
        title="Ce qu’on me demande souvent"
        subtitle="Une autre question ? Écrivez-moi, je réponds personnellement."
      />
    </div>

    <div class="reveal border-t border-line" style="--i:1" x-intersect.once="$el.classList.add('is-visible')">
      @foreach ($faqs as $i => [$q, $a])
        <x-ui.accordion :title="$q" :open="$i === 0">{{ $a }}</x-ui.accordion>
      @endforeach
    </div>
  </div>
</x-layout.section>
