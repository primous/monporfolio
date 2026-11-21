<x-layouts::app
  title="Mentions légales du site internet | Hounkpatin Primous"
  description="Mentions légales du site de Hounkpatin Primous, créateur de sites internet à Cotonou : éditeur, hébergeur, propriété intellectuelle et droit applicable."
>
  @php
    $legal = config('site.legal');
    $email = config('site.email');
    $tel = config('site.phone');
  @endphp

  <x-layout.page-hero badge="Mentions légales" title="Mentions légales" :intro="'Dernière mise à jour : '.$legal['mise_a_jour'].'.'" />

  <x-layout.section bg="white">
    <article class="prose-article mx-auto max-w-[720px]">
      <h2>Éditeur du site</h2>
      <p>
        Ce site est édité par <strong>Hounkpatin Primous</strong>, créateur de sites internet.
        @if ($legal['statut']) <br>Statut : {{ $legal['statut'] }} @endif
        @if ($legal['ifu']) <br>IFU : {{ $legal['ifu'] }} @endif
        @if ($legal['rccm']) <br>RCCM : {{ $legal['rccm'] }} @endif
        @if ($legal['adresse']) <br>Adresse : {{ $legal['adresse'] }} @endif
        @if ($email) <br>E-mail : <x-ui.email-protege /> @endif
        @if ($tel) <br>Téléphone : <a href="tel:{{ preg_replace('/[^0-9+]/', '', $tel) }}">{{ $tel }}</a> @endif
      </p>

      <h2>Directeur de la publication</h2>
      <p>Hounkpatin Primous.</p>

      <h2>Hébergement</h2>
      <p>
        Le site est hébergé par <strong>{{ $legal['hebergeur']['nom'] }}</strong>, {{ $legal['hebergeur']['adresse'] }}.
        Site&nbsp;: <a href="{{ $legal['hebergeur']['site'] }}" target="_blank" rel="noopener">{{ str_replace('https://', '', $legal['hebergeur']['site']) }}</a>.
      </p>

      <h2>Propriété intellectuelle</h2>
      <p>
        Les textes, la mise en page, le logo et les éléments graphiques de ce site sont la propriété de Hounkpatin Primous, sauf mention contraire.
        Toute reproduction, totale ou partielle, sans autorisation écrite préalable est interdite.
      </p>
      <p>
        Les captures des projets présentés dans les réalisations sont reproduites pour illustrer le travail effectué.
        Les marques et les contenus de ces projets restent la propriété de leurs titulaires respectifs.
      </p>

      <h2>Responsabilité</h2>
      <p>
        Les informations publiées sur ce site sont fournies à titre indicatif et mises à jour régulièrement.
        Malgré le soin apporté, elles peuvent comporter des inexactitudes&nbsp;: signalez-les à l’adresse de contact ci-dessus, elles seront corrigées rapidement.
      </p>
      <p>
        Le site contient des liens vers d’autres sites (WhatsApp, Facebook, sites des projets présentés).
        Leur contenu et leurs pratiques ne relèvent pas de la responsabilité de l’éditeur.
      </p>

      <h2>Données personnelles</h2>
      <p>
        Les informations envoyées par les formulaires du site sont traitées comme décrit dans la
        <a href="{{ route('politique-confidentialite') }}">politique de confidentialité</a>.
      </p>

      <h2>Droit applicable</h2>
      <p>
        Les présentes mentions légales sont soumises au droit de la République du Bénin, notamment à la loi n°&nbsp;2017-20 portant code du numérique.
      </p>
    </article>
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
