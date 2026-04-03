<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * @method bool hasRole(string|array $roles, string|null $guard = null)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, HasRoles, HasFactory;

    protected $guard_name = 'sanctum';

    protected $fillable = [
        'organisation_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'avatar_initials',
        'is_active',
        'is_confirm',
        'last_login_at'
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function ticketsCreated()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function ticketsAssigned()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketComment::class, 'author_id');
    }

    public function ticketsAssignedByMe()
    {
        return $this->hasMany(Ticket::class, 'assigned_by');
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function getRoleIdAttribute()
    {
        return $this->roles->first()?->id ?? 2;
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->assignRole('client');
        });
    }
}
