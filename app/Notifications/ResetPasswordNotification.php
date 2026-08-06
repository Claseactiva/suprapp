<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de Contraseña - SupraApp')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->level('info')
            ->line('Recibiste este correo porque solicitaste recuperar tu contraseña en SupraApp.')
            ->action('Restablecer mi contraseña', $url)
            ->line('Este link es válido por 60 minutos. Si no solicitaste esto, puedes ignorar este correo.')
            ->salutation('Saludos');
    }
}
