<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListVehicleObservacionImagen extends Model
{
    protected $table = 'check_list_vehicle_observaciones_images';
    protected $fillable = [
        'check_list_vehicle_observaciones_id', 'imagen'
    ];
}
