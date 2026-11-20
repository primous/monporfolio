<x-layouts::app
  title="Création de site internet à Cotonou | Hounkpatin Primous"
  description="Création de sites internet à Cotonou pour commerçants, artisans et indépendants : un site clair qui fait écrire vos clients. Premier échange gratuit."
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
