<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailclientImage extends Model
{
    protected $fillable = ['detailclient_id', 'imagen'];

    public function detailclient()
    {
        return $this->belongsTo('App\Models\Detailclient');
    }
}
