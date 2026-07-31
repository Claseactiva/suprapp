<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SetInitialPasswordNotification extends Notification
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
            ->subject('Bienvenido a SupraApp - Configura tu contraseña')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->level('info')
            ->line('Se creó una cuenta para ti en SupraApp.')
            ->line('Para poder ingresar, primero debes configurar tu contraseña.')
            ->action('Configurar mi contraseña', $url)
            ->line('Este link es válido por 60 minutos. Si no esperabas este correo, puedes ignorarlo.')
            ->salutation('Saludos');
    }
}
