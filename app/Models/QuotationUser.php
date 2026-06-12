<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationUser extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'quotation_id'
    ];

    public function vehicles(){
        return $this->hasMany('App\Models\QuotationUserVehicle');
    }

    public function descriptions(){
        return $this->hasMany('App\Models\QuotationUserDescription');
    }
}
