<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
        $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Postavljanje nove lozinke — PIUS ACADEMY')
            ->greeting('Pozdrav, '.$notifiable->first_name.'!')
            ->line('Primili smo zahtjev za postavljanje nove lozinke za vaš PIUS ACADEMY nalog.')
            ->action('Postavi novu lozinku', $url)
            ->line("Ovaj sigurnosni link vrijedi {$minutes} minuta i može se iskoristiti samo jednom.")
            ->line('Ako niste poslali ovaj zahtjev, nije potrebno ništa poduzeti.')
            ->salutation('PIUS ACADEMY tim');
    }
}
