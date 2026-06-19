<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

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
}
