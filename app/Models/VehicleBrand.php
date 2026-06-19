<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $fillable = [
       'id', 'brand'
    ];

    public function vehiclemodels()
    {
        return $this->hasMany('App\Models\VehicleModel','brand_id');
    }

    public function scopeBrand($query)
    {
        $keyword = request('brand');
        if ($query) {
            $query->where('brand', 'LIKE', '%'. $keyword . '%');
        }
        return $query;
    }
}
