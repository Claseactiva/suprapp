<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajos extends Model
{
    protected $fillable = [
        'orden_trabajo_id', 'descripcion', 'realizado', 'km'
    ];

    public function orden_trabajos()
    {
        return $this->belongsTo('App\Models\OrdenTrabajo');
    }

    public function imagenes()
    {
        return $this->hasMany('App\Models\ImageOrdenTrabajo', 'trabajo_id'); 
    }

    public function observaciones()
    {
        return $this->hasMany('App\Models\Observacion', 'trabajo_id'); 
    }
}
