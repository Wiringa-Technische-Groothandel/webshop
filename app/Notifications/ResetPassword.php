<?php

declare(strict_types=1);

namespace WTG\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('U ontvangt deze email omdat wij een wachtwoord reset verzoek ontvangen hebben voor uw account.')
            ->action('Reset Wachtwoord', url(config('app.url') . route('auth.password.reset', $this->token, false)))
            ->line('Als u geen wachtwoord reset aangevraagd heeft, hoeft u verder geen actie te ondernemen.');
    }
}
