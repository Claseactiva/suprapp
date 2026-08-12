<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = ['motor_number', 'numero_interno', 'modelo_motor', 'arreglo_cpl', 'created_by'];

    public function assignments()
    {
        return $this->hasMany('App\Models\MotorAssignment');
    }

    public function currentAssignment()
    {
        return $this->hasOne('App\Models\MotorAssignment')->whereNull('fecha_fin');
    }
}
