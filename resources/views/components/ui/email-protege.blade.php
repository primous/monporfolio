{{--
  E-mail protégé : l'adresse n'apparaît jamais en clair dans le HTML (ni texte, ni mailto:),
  donc invisible aux robots qui aspirent les adresses et aux recherches Google « "@domaine" ».
  Elle est stockée inversée et encodée (base64), puis reconstituée au clic par Alpine :
  1er clic = afficher l'adresse, 2e clic = ouvrir la messagerie.
  Usage : <x-ui.email-protege class="…">icône facultative</x-ui.email-protege>
--}}
@props(['libelle' => 'Afficher l’adresse e-mail'])

@php
  $email = trim((string) config('site.email'));
@endphp

@if ($email !== '')
  <a
    href="#"
    rel="nofollow"
    data-e="{{ base64_encode(strrev($email)) }}"
    data-libelle="{{ $libelle }}"
    x-data="{ e: '' }"
    @click.prevent="if (! e) { e = atob($el.dataset.e).split('').reverse().join(''); $el.href = 'mailto:' + e } else { window.location.href = $el.href }"
    {{ $attributes }}
  >{{ $slot }}<span x-text="e || $el.closest('a').dataset.libelle">{{ $libelle }}</span></a>
@endif
