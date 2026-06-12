<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation');
    }
}
