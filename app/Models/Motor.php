<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = ['motor_number', 'arreglo_cpl'];

    public function assignments()
    {
        return $this->hasMany('App\Models\MotorAssignment');
    }

    public function currentAssignment()
    {
        return $this->hasOne('App\Models\MotorAssignment')->whereNull('fecha_fin');
    }
}
