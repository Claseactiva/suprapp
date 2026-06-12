<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListVehicleObservacion extends Model
{
    protected $table = 'check_list_vehicle_observaciones';
    protected $fillable = [
        'check_list_intervencion_id','check_list_vehicle_id', 'observacion'
    ];


    public function imagenes()
    {
        return $this->hasMany(CheckListVehicleObservacionImagen::class, 'check_list_vehicle_observaciones_id', 'id');
    }
}
