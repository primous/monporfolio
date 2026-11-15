<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleDemande extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Demande $demande)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $difficultiesStr = is_array($this->demande->difficulties)
            ? implode(', ', $this->demande->difficulties)
            : (string) $this->demande->difficulties;

        $budgetDisplay = $this->demande->budget_unknown
            ? 'Je ne sais pas encore, conseillez-moi'
            : ($this->demande->budget ?? 'Non renseigné');

        // Fiche de la demande dans le panel Filament « admin » (route générée par DemandeResource).
        $adminUrl = route('filament.admin.resources.demandes.view', ['record' => $this->demande]);

        return (new MailMessage)
            ->subject('Nouvelle demande de projet')
            ->greeting('Bonjour ' . config('site.owner') . ',')
            ->line('Une nouvelle demande de projet vient d’être envoyée depuis votre site internet.')
            ->line('**Nom complet :** ' . $this->demande->full_name)
            ->line('**Email :** ' . $this->demande->email)
            ->line('**Téléphone / WhatsApp :** ' . $this->demande->phone)
            ->line('**Activité :** ' . $this->demande->activity)
            ->line('**Site internet actuel :** ' . $this->demande->has_website)
            ->line('**Difficultés rencontrées :** ' . $difficultiesStr)
            ->when($this->demande->main_difficulty, function ($mail) {
                return $mail->line('**Difficulté principale :** ' . $this->demande->main_difficulty);
            })
            ->line('**Objectif du site :** ' . $this->demande->objective)
            ->line('**Description du besoin :** ' . $this->demande->need)
            ->line('**Budget prévu :** ' . $budgetDisplay)
            ->action('Consulter la demande dans Filament', $adminUrl)
            ->line('Date et heure de réception : ' . $this->demande->created_at->format('d/m/Y à H:i'));
    }
}

