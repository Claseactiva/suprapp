<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleEngine extends Model
{
    protected $fillable = [
        'vehicle_model_id', 'motor_spec_id', 'year_from', 'year_to'
    ];

    public function vehicleModel()
    {
        return $this->belongsTo('App\Models\VehicleModel', 'vehicle_model_id');
    }

    public function motorSpec()
    {
        return $this->belongsTo('App\Models\MotorSpec', 'motor_spec_id');
    }
}
