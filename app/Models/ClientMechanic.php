<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMechanic extends Model
{
    protected $fillable = ['client_id', 'mechanic_id'];
}
