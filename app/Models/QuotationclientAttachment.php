<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationclientAttachment extends Model
{
    protected $fillable = ['quotationclient_id', 'path', 'original_name', 'mime_type', 'size'];

    public function quotationclient()
    {
        return $this->belongsTo('App\Models\Quotationclient');
    }
}
