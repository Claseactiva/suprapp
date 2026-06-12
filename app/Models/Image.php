<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;


class Image extends Model
{
    protected $fillable = [
        'detail_vehicle_id', 'url'
     ];

    public function detail_vehicle()
    {
        return $this->belongsTo('App\Models\DetailVehicle');
    }
}
