<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = ['motor_number', 'numero_interno', 'modelo_motor', 'arreglo_cpl', 'created_by', 'client_id'];

    public function assignments()
    {
        return $this->hasMany('App\Models\MotorAssignment');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function currentAssignment()
    {
        return $this->hasOne('App\Models\MotorAssignment')->whereNull('fecha_fin');
    }
}
