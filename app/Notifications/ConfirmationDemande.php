<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationDemande extends Notification
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
        $phone = (string) config('site.phone');
        $phoneClean = preg_replace('/[^0-9+]/', '', $phone);
        $phoneUrl = 'tel:' . ($phoneClean ?: $phone);

        $whatsapp = (string) config('site.whatsapp');
        $whatsappClean = preg_replace('/[^0-9]/', '', $whatsapp);
        $whatsappMessage = (string) config('site.whatsapp_message');
        $whatsappUrl = 'https://wa.me/' . $whatsappClean . '?text=' . urlencode($whatsappMessage);

        $mail = (new MailMessage)
            ->subject('Merci, votre demande est bien arrivée.')
            ->greeting('Bonjour ' . $this->demande->full_name . ',')
            ->line('Merci, votre demande est bien arrivée.')
            ->line('Je lis vos réponses avec attention et je reviens vers vous avec une proposition. Vous pouvez aussi m’écrire dès maintenant.')
            ->line('[Appeler](' . $phoneUrl . ')')
            ->action('WhatsApp', $whatsappUrl)
            ->salutation('Hounkpatin Primous');

        $notifyEmail = config('site.notify_email');
        if (!empty($notifyEmail)) {
            $mail->replyTo($notifyEmail);
        }

        return $mail;
    }
}
