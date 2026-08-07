<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function activities()
    {
        return $this->hasMany('App\Models\Activity');
    }

    public function quotationclients()
    {
        return $this->hasMany('App\Models\Quotationclient');
    }

    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'supplier_id');
    }

    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }


    public function scopeType($query)
    {
        $keyword = request('type');

        if (is_array($keyword)) {
            $query->whereIn('type', $keyword);
        } elseif ($keyword) {
            $query->where('type', '=', $keyword);
        }

        return $query;
    }
    /**
     * Filter user data based on query
     *
     * @params $query
     * @return Eloquent
     */
    public function scopeRazonSocial($query)
    {
        $keyword = request('client');
        if ($query) {
            $query->where('razonSocial', 'LIKE', '%'. $keyword . '%');
        }
        return $query;
    }
}
