<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MechanicClient extends Model
{
    public $table = 'mechanic_client';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'mechanic_id'
    ];
}
