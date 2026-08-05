<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackgroundImage extends Model
{
    protected $fillable = ['path', 'is_light', 'uploaded_by'];

    protected $casts = [
        'is_light' => 'boolean',
    ];
}
