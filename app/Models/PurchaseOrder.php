<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * "Proveedor" es un Client (tabla clients, misma que usa "Empresas / Proveedores"),
     * no una entidad separada.
     */
    public function supplier()
    {
        return $this->belongsTo('App\Models\Client', 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\PurchaseOrderDetail');
    }

    /**
     * Filter user data based on query
     *
     * @params $query
     * @return Eloquent
     */
    public function scopeId($query)
    {
        $keyword = request('id');
        if ($query) {
            $query->where('id', 'LIKE', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * Filter user data based on query
     *
     * @params $query
     * @return Eloquent
     */
    public function scopeDate($query)
    {
        $day = request('day');
        $month = request('month');
        $year = request('year');
        if ($query) {
            if ($day != '') {
                $query->whereDay('created_at', $day);
            }
            if ($month != '') {
                $query->whereMonth('created_at', $month);
            }
            if ($year != '') {
                $query->whereYear('created_at', $year);
            }
        }
        return $query;
    }
}
