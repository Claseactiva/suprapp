<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListVehicleCategoria extends Model
{
    protected $fillable = [
        'check_list_vehicle_id','categoria'
    ];


    public function intervenciones()
    {
        return $this->hasMany(CheckListVehicleIntervencion::class, 'check_list_categoria_id', 'id');
    }
}
