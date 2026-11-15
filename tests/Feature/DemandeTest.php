<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Notifications\ConfirmationDemande;
use App\Notifications\NouvelleDemande;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DemandeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Payload valide pour le formulaire de demande de projet.
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'activity' => 'Commerce en ligne et vente de vêtements',
            'has_website' => 'Non, aucun site existant',
            'difficulties' => ['Manque de visibilité', 'Aucun outil de commande'],
            'main_difficulty' => 'Je ne sais pas comment attirer des clients en ligne.',
            'objective' => 'Vendre mes produits en ligne avec paiement sécurisé.',
            'need' => 'Site e-commerce complet avec catalogue.',
            'budget_unknown' => false,
            'budget' => '1 000 000 - 2 000 000 FCFA',
            'full_name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '+229 97 00 00 00',
            'consent' => true,
            'website' => '', // honeypot vide
        ], $overrides);
    }

    /**
     * Test 1 : Demande valide -> 201, ligne en base et notification envoyée.
     */
    public function test_demande_valide_enregistree_en_base_et_notifiee(): void
    {
        Mail::fake();
        Notification::fake();

        config(['site.notify_email' => 'admin@example.com']);

        $response = $this->postJson(route('demande-de-projet.store'), $this->validPayload());

        $response->assertStatus(201)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('demandes', [
            'full_name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'statut' => 'nouveau',
        ]);

        // Vérifie que DEUX notifications sont envoyées : admin et confirmation visiteur
        Notification::assertSentOnDemand(NouvelleDemande::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'admin@example.com';
        });

        Notification::assertSentOnDemand(ConfirmationDemande::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'jean.dupont@example.com';
        });

        Notification::assertSentTimes(NouvelleDemande::class, 1);
        Notification::assertSentTimes(ConfirmationDemande::class, 1);
    }

    /**
     * Test 2 : Demande invalide -> 422, aucune ligne créée en base, aucune notification.
     */
    public function test_demande_invalide_retourne_422_et_ne_cree_rien(): void
    {
        Mail::fake();
        Notification::fake();

        $response = $this->postJson(route('demande-de-projet.store'), [
            'email' => 'email-invalide',
            'consent' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'activity',
                'has_website',
                'difficulties',
                'objective',
                'need',
                'full_name',
                'email',
                'phone',
                'consent',
            ]);

        $this->assertDatabaseCount('demandes', 0);
        Notification::assertNothingSent();
    }

    /**
     * Test 3 : Honeypot rempli -> 201 sans créer de ligne ni notifier.
     */
    public function test_honeypot_rempli_retourne_201_sans_enregistrer_ni_notifier(): void
    {
        Mail::fake();
        Notification::fake();

        $payload = $this->validPayload([
            'website' => 'http://spambot-link.com',
        ]);

        $response = $this->postJson(route('demande-de-projet.store'), $payload);

        $response->assertStatus(201)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseCount('demandes', 0);
        Notification::assertNothingSent();
    }

    /**
     * Test 4 : Limite de débit -> 429 après 5 tentatives (throttle:5,10).
     */
    public function test_limite_de_debit_retourne_429_apres_5_tentatives(): void
    {
        Mail::fake();
        Notification::fake();

        $payload = $this->validPayload();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('demande-de-projet.store'), $payload)->assertStatus(201);
        }

        $response = $this->postJson(route('demande-de-projet.store'), $payload);
        $response->assertStatus(429);
    }

    /**
     * Test 5 : Tranches de budget et budget libre ou inconnu.
     */
    public function test_tranches_budget_et_options_valides(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        Mail::fake();
        Notification::fake();

        $options = [
            'Moins de 100 000 FCFA',
            '100k – 300k FCFA',
            '300k – 500k FCFA',
            '500k – 1M FCFA',
            'Plus de 1M FCFA',
            '750 000 FCFA',
        ];

        foreach ($options as $idx => $opt) {
            $payload = $this->validPayload([
                'email' => "client{$idx}@example.com",
                'budget' => $opt,
                'budget_unknown' => false,
            ]);

            $this->postJson(route('demande-de-projet.store'), $payload)
                ->assertStatus(201);

            $this->assertDatabaseHas('demandes', [
                'email' => "client{$idx}@example.com",
                'budget' => $opt,
            ]);
        }

        // Test budget inconnu
        $payloadUnknown = $this->validPayload([
            'email' => 'client.unknown@example.com',
            'budget' => null,
            'budget_unknown' => true,
        ]);

        $this->postJson(route('demande-de-projet.store'), $payloadUnknown)
            ->assertStatus(201);

        $this->assertDatabaseHas('demandes', [
            'email' => 'client.unknown@example.com',
            'budget_unknown' => true,
        ]);
    }

    /**
     * Test 6 : Contenu exact de l'e-mail de confirmation au visiteur.
     */
    public function test_contenu_notification_confirmation_visiteur(): void
    {
        config([
            'site.notify_email' => 'admin@example.com',
            'site.phone' => '+229 97 00 00 00',
            'site.whatsapp' => '+229 97 00 00 00',
            'site.whatsapp_message' => 'Bonjour, je vous contacte depuis votre site.',
        ]);

        $demande = new Demande([
            'full_name' => 'Marc Dossou',
            'email' => 'marc@example.com',
        ]);

        $notification = new ConfirmationDemande($demande);
        $mail = $notification->toMail(new \stdClass());

        $this->assertSame('Merci, votre demande est bien arrivée.', $mail->subject);
        $this->assertSame('Bonjour Marc Dossou,', $mail->greeting);
        $this->assertSame('Hounkpatin Primous', $mail->salutation);
        $this->assertContains('Merci, votre demande est bien arrivée.', $mail->introLines);
        $this->assertContains('Je lis vos réponses avec attention et je reviens vers vous avec une proposition. Vous pouvez aussi m’écrire dès maintenant.', $mail->introLines);
        $this->assertContains('[Appeler](tel:+22997000000)', $mail->introLines);
        $this->assertSame('WhatsApp', $mail->actionText);
        $this->assertStringContainsString('https://wa.me/22997000000', $mail->actionUrl);
        $this->assertSame('admin@example.com', $mail->replyTo[0][0] ?? null);
    }
}

