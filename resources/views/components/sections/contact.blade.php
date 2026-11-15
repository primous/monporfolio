<x-layout.section bg="surface" id="contact">
  <div class="mx-auto max-w-[760px]">
    <div class="reveal" style="--i:0" x-intersect.once="$el.classList.add('is-visible')">
      <x-ui.section-heading
        badge="Contact"
        title="Parlons de votre projet"
        subtitle="Quelques lignes suffisent. Je vous réponds avec une première proposition, sans engagement."
      />
    </div>

    <div class="reveal mt-10 rounded-[24px] border border-line bg-white p-6 shadow-card md:p-8" style="--i:1" x-intersect.once="$el.classList.add('is-visible')">
      @if (session('contact_sent'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3" role="status">
          <x-ui.check-item>Merci, votre message est bien envoyé. Je vous réponds rapidement.</x-ui.check-item>
        </div>
      @endif

      <form method="POST" action="{{ route('contact.store') }}#contact" class="grid gap-5 md:grid-cols-2" novalidate>
        @csrf
        <x-ui.field label="Nom" name="name" autocomplete="name" required />
        <x-ui.field label="E-mail" name="email" type="email" autocomplete="email" required />
        <x-ui.field label="Votre projet" name="message" textarea required class="md:col-span-2"
          placeholder="Activité, objectif du site, délai souhaité…" />
        <div class="md:col-span-2">
          <x-ui.button type="submit" class="w-full justify-center sm:w-auto">Envoyer ma demande</x-ui.button>
        </div>
      </form>
    </div>
  </div>
</x-layout.section>
