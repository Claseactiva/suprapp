<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{

    protected $table = 'vehicle_tipos';

    protected $fillable = [
        'id', 'tipo_vehiculo'
     ];
     
     public function scopeTipo($query)
     {
         $keyword = request('tipo_vehiculo');
         if ($query) {
             $query->where('tipo_vehiculo', 'LIKE', '%'. $keyword . '%');
         }
         return $query;
     }
}
