<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListVehicle extends Model
{
    protected $fillable = [
        'check_list_id' , 'vehicle_id', 'realizado'
    ];
}
