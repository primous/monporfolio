<x-layouts::app
  title="Politique de confidentialité | Primous"
  description="Quelles données sont collectées sur ce site, pourquoi, combien de temps elles sont conservées et comment exercer vos droits."
>
  @php
    $legal = config('site.legal');
    $email = config('site.email');
    $cookie = config('session.cookie');
    $duree = (int) config('session.lifetime');
  @endphp

  <x-layout.page-hero
    badge="Politique de confidentialité"
    title="Politique de confidentialité"
    :intro="'Ce que deviennent les informations que vous me confiez. Dernière mise à jour : '.$legal['mise_a_jour'].'.'"
  />

  <x-layout.section bg="white">
    <article class="prose-article mx-auto max-w-[720px]">
      <p>
        Je ne collecte que les informations nécessaires pour répondre à vos demandes.
        Elles ne sont <strong>jamais vendues, louées ni cédées</strong> à des tiers, et ce site n’utilise <strong>aucun outil de suivi publicitaire ni de mesure d’audience externe</strong>.
      </p>

      <h2>Responsable du traitement</h2>
      <p>
        Hounkpatin Primous, créateur de sites internet
        @if ($legal['adresse']) ({{ $legal['adresse'] }})@endif.
        @if ($email) Contact&nbsp;: <a href="mailto:{{ $email }}">{{ $email }}</a>. @endif
      </p>

      <h2>Les données collectées, et pourquoi</h2>

      <h3>Demande de projet</h3>
      <p>
        Votre activité, votre site actuel, vos difficultés, votre objectif, votre besoin, votre budget, votre nom, votre e-mail et votre téléphone.
        <br><strong>Pourquoi&nbsp;:</strong> comprendre votre projet, vous recontacter et vous envoyer une proposition.
        <br><strong>Base&nbsp;:</strong> votre consentement, donné en cochant la case du formulaire, et les échanges préalables à un éventuel contrat.
        <br><strong>Durée&nbsp;:</strong> 3 ans après notre dernier échange, puis suppression.
      </p>

      <h3>Commentaires du blog</h3>
      <p>
        Le nom que vous indiquez et votre message. Ils sont publiés sous l’article après relecture.
        <br><strong>Durée&nbsp;:</strong> tant que le commentaire est en ligne, ou jusqu’à votre demande de suppression.
      </p>

      <h3>Avis clients</h3>
      <p>
        Votre nom, votre activité (facultative) et votre avis. Ils sont publiés sur la page Avis après relecture, uniquement si vous avez coché la case d’accord.
        <br><strong>Durée&nbsp;:</strong> tant que l’avis est en ligne, ou jusqu’à votre demande de suppression.
      </p>

      <h3>Suggestion anonyme</h3>
      <p>
        Uniquement votre message et le chemin de la page d’où il a été envoyé.
        Aucun nom, aucune adresse e-mail et aucune adresse IP n’est enregistrée.
        <br><strong>Durée&nbsp;:</strong> 12 mois.
      </p>

      <h3>Statistiques du blog</h3>
      <p>
        Le nombre de lectures d’un article est un simple compteur&nbsp;: il ne contient aucune information sur vous.
      </p>

      <h2>Qui a accès à vos données</h2>
      <p>
        Moi seul, depuis l’espace d’administration protégé du site.
        Les e-mails de confirmation et de notification passent par le service de messagerie de l’hébergeur ({{ $legal['hebergeur']['nom'] }}).
        Les données sont stockées sur les serveurs de cet hébergeur.
      </p>

      <h2>Cookies</h2>
      <p>Ce site n’utilise que deux cookies, strictement nécessaires à son fonctionnement, qui ne servent ni à vous suivre ni à la publicité&nbsp;:</p>
      <ul>
        <li><strong>{{ $cookie }}</strong>&nbsp;: maintient votre session de navigation ({{ $duree }} minutes) et évite de compter deux fois la lecture d’un article&nbsp;;</li>
        <li><strong>XSRF-TOKEN</strong>&nbsp;: protège les formulaires contre les envois frauduleux.</li>
      </ul>
      <p>Les polices du site sont hébergées sur le site lui-même&nbsp;: aucune requête n’est envoyée à Google ou à un autre service pour les afficher.</p>
      <p>Les boutons Facebook et WhatsApp sont de simples liens&nbsp;: ils ne chargent rien tant que vous ne cliquez pas.</p>

      <h2>Sécurité</h2>
      <p>
        Les formulaires sont protégés contre les envois automatisés et les abus (limitation du nombre d’envois),
        et l’espace d’administration n’est accessible qu’avec un compte protégé par mot de passe.
      </p>

      <h2>Vos droits</h2>
      <p>Vous pouvez à tout moment&nbsp;:</p>
      <ul>
        <li>savoir quelles données vous concernant sont conservées&nbsp;;</li>
        <li>les faire corriger ou supprimer&nbsp;;</li>
        <li>retirer votre accord, par exemple pour qu’un avis ou un commentaire soit retiré&nbsp;;</li>
        <li>vous opposer à leur utilisation.</li>
      </ul>
      <p>
        @if ($email)
          Écrivez simplement à <a href="mailto:{{ $email }}">{{ $email }}</a>&nbsp;: je vous réponds dans les meilleurs délais, et au plus tard sous un mois.
        @else
          Contactez-moi par téléphone ou par WhatsApp&nbsp;: je vous réponds dans les meilleurs délais, et au plus tard sous un mois.
        @endif
      </p>
      <p>
        Si vous estimez que vos droits ne sont pas respectés, vous pouvez saisir l’Autorité de Protection des Données à caractère Personnel du Bénin (APDP).
      </p>

      <h2>Modifications</h2>
      <p>Cette politique peut évoluer avec le site. La date de dernière mise à jour figure en haut de cette page.</p>
    </article>
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
