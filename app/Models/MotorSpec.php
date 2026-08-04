<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorSpec extends Model
{
    protected $fillable = ['cilindrada', 'combustible', 'raw_label'];

    public function vehicleEngines()
    {
        return $this->hasMany('App\Models\VehicleEngine', 'motor_spec_id');
    }
}
