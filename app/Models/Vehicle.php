<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
       'id', 'user_id', 'patent', 'chasis', 'brand', 'model', 'year', 'engine', 'color', 'km'
    ];

    public function detail_vehicles()
    {
        return $this->hasMany('App\Models\DetailVehicle');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopePatent($query)
    {
        $keyword = request('patent');
        if ($query) {
            $query->where('patent', 'LIKE', '%'. $keyword . '%');
        }
        return $query;
    }

    public function scopeName($query)
    {
        $keyword = request('name');
        if ($query) {
            $query->where('brand', 'LIKE', '%'. $keyword . '%');
        }
        return $query;
    }

    public function scopeYear($query)
    {
        $keyword = request('year');
        if ($query && is_numeric($keyword)) {
            $query->where('year', '=',$keyword);
        }
        return $query;
    }

    public function checklist()
    {
        return $this->hasMany('App\Models\CheckListVehicle');
    }
}
