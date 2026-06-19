<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones';
    protected $fillable = [
        'trabajo_id', 'observacion' ,'url'
    ];


    public function imagenes()
    {
        return $this->hasMany(ObservacionImagen::class, 'observaciones_id', 'id');
    }
}
