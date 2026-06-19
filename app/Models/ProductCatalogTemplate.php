<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogTemplate extends Model
{
    protected $fillable = [
        'categoria',
        'nombre',
    ];
}
