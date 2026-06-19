<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleEngine extends Model
{
    protected $fillable = [
        'id', 'year_id', 'v_engine'
    ];

    public function vehicleYear()
    {
        return $this->belongsTo('App\Models\VehicleYear');
    }
}
