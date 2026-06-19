<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = [
       'id','brand_id', 'model', 'tipo_id'
    ];

    public function vehiclebrand()
    {
        return $this->belongsTo('App\Models\VehicleBrand');
    }
    
    public function scopeBrand($query)
    {
        $keyword = request('brand_id');
        if ($query && $keyword!=null && is_numeric($keyword)) {
            $query->where('brand_id', '=',$keyword);
            return $query;
        }
    }
    public function scopeModel($query)
    {
        $keyword = request('model');
        if ($query) {
            $query->where('model', 'LIKE', '%'. $keyword . '%');
        }
        return $query;
    }
}
