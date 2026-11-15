@props(['bg' => null])
@php
// Fond : alterne avec la section précédente (« Avis » si elle existe, sinon « about » en blanc).
// Fond : imposé par la page, sinon alterne avec la section « Avis » de l'accueil (présente si des avis sont publiés).
$bg = $bg ?? (\App\Models\Avis::publies()->exists() ? 'white' : 'surface');

// Réponses volontairement sans chiffre inventé : prix et délai sont donnés dans la proposition.
$faqs = [
    ['Combien coûte un site ?', 'Cela dépend de ce dont vous avez besoin : une page simple ou un site plus complet. Après avoir lu votre demande, je vous envoie une proposition avec un prix précis, avant tout engagement.'],
    ['Combien de temps faut-il ?', 'Le délai dépend du nombre de pages et des contenus à préparer. Il est indiqué dans ma proposition, pour que vous sachiez à quoi vous attendre.'],
    ['Je n’ai jamais eu de site. Est-ce pour moi ?', 'Oui. Vous décrivez votre activité avec vos mots, sans jargon, et je vous guide à chaque étape.'],
    ['Et une fois le site en ligne ?', 'Je ne vous laisse pas seul : je vous aide à faire venir des visiteurs sur votre site.'],
];

// Typographie française : espace insécable avant « ? » et « : ».
$nbsp = fn (string $t) => str_replace([' ?', ' :'], ["\u{00A0}?", "\u{00A0}:"], $t);
$faqs = array_map(fn ($f) => [$nbsp($f[0]), $nbsp($f[1])], $faqs);
@endphp

<x-layout.section :bg="$bg" id="questions">
  <div class="grid gap-10 lg:grid-cols-[1fr_1.4fr] lg:gap-16">
    <div class="reveal" style="--i:0">
      <x-ui.section-heading display
        align="left"
        badge="Questions fréquentes"
        title="Ce que vous vous demandez sûrement"
      />
    </div>

    <div class="reveal border-t border-line" style="--i:1">
      @foreach ($faqs as $n => [$q, $a])
        <x-ui.accordion :title="$q" :open="$n === 0">{{ $a }}</x-ui.accordion>
      @endforeach
    </div>
  </div>
</x-layout.section>
