<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Te llegó este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer mi contraseña', url(config('app.url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email))
            ->line('Si no solicitaste un restablecimiento de contraseña, no hace falta ninguna acción.');
    }
}
