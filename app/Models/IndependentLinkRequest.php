<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndependentLinkRequest extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_user_id');
    }
}
