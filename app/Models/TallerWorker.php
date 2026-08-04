<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallerWorker extends Model
{
    public $table = 'taller_workers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'taller_id'
    ];
}
