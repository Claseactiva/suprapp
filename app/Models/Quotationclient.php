<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotationclient extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function detailclients()
    {
        return $this->hasMany('App\Models\Detailclient');
    }

    public function vehicleModel()
    {
        return $this->belongsTo('App\Models\VehicleModel', 'vehicle_model_id');
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
            $query->where('id', 'LIKE', '%'. $keyword . '%');
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
            if($day!=''){
                $query->whereDay('created_at',$day);
            }
            if($month!=''){
                $query->whereMonth('created_at',$month);
            }
            if($year!=''){
                $query->whereYear('created_at',$year);
            }
        }
        return $query;
    }
}
