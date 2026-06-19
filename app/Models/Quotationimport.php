<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotationimport extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function import()
    {
        return $this->belongsTo('App\Models\Import');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
