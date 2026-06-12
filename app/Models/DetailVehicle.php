<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailVehicle extends Model
{
    protected $fillable = [
        'vehicle_id', 'km', 'note'
     ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

}
