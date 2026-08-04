<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrandModel extends Model
{

    protected $fillable = [
        'id', 'brand', 'model', 'tipo_id'
    ];
    
    public $timestamps = false;

}
