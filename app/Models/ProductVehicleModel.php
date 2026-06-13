<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVehicleModel extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function vehicleModel()
    {
        return $this->belongsTo('App\Models\VehicleModel');
    }
}
