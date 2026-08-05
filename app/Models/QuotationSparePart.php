<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationSparePart extends Model
{
    protected $fillable = ['quotationclient_id', 'product_id', 'product', 'detail', 'quantity'];

    public function quotationclient()
    {
        return $this->belongsTo('App\Models\Quotationclient');
    }

    public function images()
    {
        return $this->hasMany('App\Models\QuotationSparePartImage');
    }
}
