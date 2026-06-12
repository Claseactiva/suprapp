<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryTime extends Model
{
    protected $fillable = [
        'label',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
