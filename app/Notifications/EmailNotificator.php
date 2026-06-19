<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotificator extends Notification
{
    use Queueable;

    protected $name;
    protected $email;
    protected $phone;
    protected $patentchasis;
    protected $description;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $email, $phone, $patentchasis, $description)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->patentchasis = $patentchasis;
        $this->description = $description;
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
            ->subject('Nueva Cotización') // it will use this class name if you don't specify
            ->greeting('Estimado:') // example: Dear Sir, Hello Madam, etc ...
            ->level('info')// It is kind of email. Available options: info, success, error. Default: info
            ->line('Tiene una nueva cotización pendiente.')
            ->line('Nombre: '.$this->name)
            ->line('Correo: '.$this->email)
            ->line('Teléfono: '.$this->phone)
            ->line('Patente o Chasis: '.$this->patentchasis)
            ->line('Repuestos a solicitar: '.$this->description)
            ->action('Ir a PortalApp', url('/admin-cotizaciones-formales'))
/*             ->line('Thank you for using our application!') */
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
