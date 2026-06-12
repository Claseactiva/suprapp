<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleYear extends Model
{
    protected $fillable = [
        'id', 'v_id', 'v_year'
    ];

    
    public function vehicleBrandModel(){
        return $this->belongsTo('App\Models\VehicleBrandModel');
    }


    public function vehicleEngine(){
        return $this->hasMany('App\Models\VehicleEngine','year_id');
    }
}
