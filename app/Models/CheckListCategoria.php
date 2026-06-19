<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckListCategoria extends Model
{
    protected $fillable = [
        'check_list_id' , 'categoria', 'count'
    ];

    public function intervenciones()
    {
        return $this->hasMany('App\Models\CheckListIntervencion');
    }

    public function intervenciones_vehicle(){
        return $this->hasMany(CheckListVehicleIntervencion::class, 'check_list_categoria_id', 'id');
        // return $this->hasMany(CheckListVehicleCondiciones::class, 
        //                              CheckListVehicleIntervencion::class, 
        //                              'check_list_categoria_id',
        //                              'check_list_intervencion_id', 
        //                              'id',
        //                              'id_intervencion');

        
    }
}
