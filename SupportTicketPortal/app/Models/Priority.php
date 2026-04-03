<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'display_order',
        'colour_hex',
        'response_hours',
        'resolution_hours',
        'is_active',
    ];

    public $timestamps = true;

    // Optional: tickets using this priority
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
