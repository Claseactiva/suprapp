<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleReferenceData extends Model
{
    protected $table = 'vehicle_reference_data';

    protected $fillable = [
        'periodo', 'cod_prt', 'ppu', 'ppu_norm', 'cod_vehiculo',
        'cod_combustible', 'cod_servicio', 'marca', 'modelo',
        'ano_fabricacion', 'num_motor', 'num_chasis',
    ];

    public $timestamps = false;
}
