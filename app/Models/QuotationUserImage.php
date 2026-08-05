<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationUserImage extends Model
{
    protected $fillable = ['quotation_user_vehicle_id', 'imagen'];

    public function quotationUserVehicle()
    {
        return $this->belongsTo('App\Models\QuotationUserVehicle');
    }
}
