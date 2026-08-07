<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailImage extends Model
{
    protected $fillable = ['purchase_order_detail_id', 'imagen'];

    public function purchaseOrderDetail()
    {
        return $this->belongsTo('App\Models\PurchaseOrderDetail');
    }
}
