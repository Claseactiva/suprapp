<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorAssignment extends Model
{
    protected $fillable = ['motor_id', 'vehicle_id', 'fecha_inicio', 'fecha_fin'];

    public function motor()
    {
        return $this->belongsTo('App\Models\Motor');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('fecha_fin');
    }
}
