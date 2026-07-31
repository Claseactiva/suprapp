<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'user_id', 'session_token', 'device_fingerprint', 'device_name',
        'ip_address', 'user_agent', 'last_seen_at', 'revoked_at'
    ];

    protected $hidden = [
        'session_token'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('revoked_at');
    }
}
