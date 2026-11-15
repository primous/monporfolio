<x-layouts::app
  title="Votre site internet, expliqué une seule fois | Primous"
  description="Un site clair pour votre activité : vos clients comprennent tout, vous arrêtez de répéter. Décrivez votre projet en 5 étapes. Premier échange gratuit."
>
  {{--
    Rythme : panneau noir (hero) / blanc (résultats) / bande noire (projets) / gris (problème)
             / blanc (transformation) / gris (étapes) / blanc (parcours) / [gris (avis)] / gris ou blanc (questions)
             / noir (CTA, enchaîné avec le footer).
    Les anciennes sections (hero, services, faq, contact) restent dans components/sections/ mais ne sont plus affichées.
  --}}
  <x-sections.home-hero />
  <x-sections.results />
  <x-sections.projects />
  <x-sections.problem />
  <x-sections.transformation />
  <x-sections.process />
  <x-sections.about />
  <x-sections.testimonials />
  <x-sections.home-faq />
  <x-sections.cta />
</x-layouts::app>
