<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
}
