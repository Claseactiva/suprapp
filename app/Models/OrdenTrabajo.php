<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    protected $fillable = [
        'vehicle_id', 'km', 'user_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }

    public function trabajo()
    {
        return $this->hasMany('App\Models\Trabajos');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

}