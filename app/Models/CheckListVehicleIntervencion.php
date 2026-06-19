<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListVehicleIntervencion extends Model
{
    protected $table = 'check_list_vehicle_intervenciones';
    protected $fillable = [
        'check_list_categoria_id', 'intervencion'
    ];

    public function condiciones()
    {
        return $this->hasMany(CheckListVehicleCondicion::class, 'check_list_intervencion_id', 'id');
    }

    public function observaciones()
    {
        return $this->hasMany(CheckListVehicleObservacion::class, 'check_list_intervencion_id', 'id');
    }
}
