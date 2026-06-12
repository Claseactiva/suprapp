<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservacionImagen extends Model
{
    protected $table = 'observaciones_images';
    protected $fillable = [
        'observaciones_id', 'imagen'
    ];
}
