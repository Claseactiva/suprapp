<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'cant_vehicle', 'url', 'token','logo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicle');
    }

    public function quotations()
    {
        return $this->hasMany('App\Models\Quotation');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function mechanic() {
        return $this->hasone('App\MechanicClient', 'user_id', 'id');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Usa una notificacion propia (en espanol) en vez de la de Laravel por defecto
     * para el flujo estandar de "olvide mi contrasena" (password/email).
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * true si es un trabajador (sub-usuario) de un taller, no el dueño.
     * Se usa para restringir la vista de OT solo a las que le fueron asignadas.
     */
    public function isTallerWorker()
    {
        return $this->hasRole('Workshop Personal');
    }

    /**
     * Id del taller bajo el que opera este usuario: el propio id si es Workshop
     * (o cualquier otro rol), o el taller vinculado si es Workshop Personal (trabajador).
     */
    public function effectiveTallerId()
    {
        if ($this->hasRole('Workshop Personal')) {
            $link = DB::table('taller_workers')->where('user_id', $this->id)->first();

            if ($link) {
                return (int) $link->taller_id;
            }
        }

        return $this->id;
    }

    /**
     * Ids de usuario del taller (el taller mismo + todos sus trabajadores),
     * usado para filtrar listados (OT, Check List, Vehiculos) sin alterar
     * el user_id con el que se crea cada registro.
     */
    public function teamUserIds()
    {
        $tallerId = $this->effectiveTallerId();

        $workerIds = DB::table('taller_workers')
            ->where('taller_id', $tallerId)
            ->pluck('user_id')
            ->all();

        return array_values(array_unique(array_merge([$tallerId], $workerIds)));
    }
}
