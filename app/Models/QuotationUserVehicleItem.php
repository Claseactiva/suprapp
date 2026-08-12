<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationUserVehicleItem extends Model
{
    protected $fillable = [
        'quotation_user_vehicle_id',
        'description',
        'qty'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\QuotationUserVehicle', 'quotation_user_vehicle_id');
    }
}
