<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function productSales()
    {
        return $this->hasMany('App\Models\ProductSale');
    }
}
