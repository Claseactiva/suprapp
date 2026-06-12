<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationUserVehicle extends Model
{
    protected $fillable = [
        'user_id',
        'patentchasis',
        'brand',
        'model',
        'year',
        'engine',
        'email',
        'description'
    ];

    public function user(){
        return $this->hasOne('App\Models\QuotationUser');
    }

    public function descriptions(){
        return $this->hasMany('App\Models\QuotationUserDescription');
    }
}
