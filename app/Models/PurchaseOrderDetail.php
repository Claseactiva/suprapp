<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function images()
    {
        return $this->hasMany('App\Models\PurchaseOrderDetailImage');
    }
}
