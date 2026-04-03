<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'token_hash',
        'role_claim',
        'issued_at',
        'expires_at',
        'revoked',
        'revoked_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
