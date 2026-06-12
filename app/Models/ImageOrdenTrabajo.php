<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageOrdenTrabajo extends Model
{
    protected $fillable = [
        'trabajo_id', 'url'
     ];

    public function trabajo()
    {
        return $this->belongsTo('App\Models\Trabajos');
    }
    
}
