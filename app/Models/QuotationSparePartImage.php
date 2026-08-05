<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationSparePartImage extends Model
{
    protected $fillable = ['quotation_spare_part_id', 'imagen'];

    public function quotationSparePart()
    {
        return $this->belongsTo('App\Models\QuotationSparePart');
    }
}
