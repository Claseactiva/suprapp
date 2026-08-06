<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ShippingNotificator extends Notification
{
    use Queueable;

    protected $shippingId;
    protected $nombre;
    protected $rut;
    protected $telefono;
    protected $ciudad;
    protected $sucursal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id, $nombre, $rut, $telefono, $ciudad, $sucursal)
    {
        $this->shippingId = $id;
        $this->nombre = $nombre;
        $this->rut = $rut;
        $this->telefono = $telefono;
        $this->ciudad = $ciudad;
        $this->sucursal = $sucursal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nuevo Envío')
            ->greeting('Estimado:')
            ->level('info')
            ->line('Se completó un nuevo formulario de envío.')
            ->line('N° Envío: ' . $this->shippingId)
            ->line('Nombre: ' . $this->nombre)
            ->line('RUT: ' . $this->rut)
            ->line('Teléfono: ' . $this->telefono)
            ->line('Ciudad: ' . $this->ciudad)
            ->line('Sucursal: ' . $this->sucursal)
            ->action('Ir a PortalApp', url('/admin-cotizaciones-formales'))
            ->salutation('Saludos');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
