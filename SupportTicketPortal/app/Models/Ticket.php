<?php

namespace App\Models;

use App\Filters\TicketFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'priority_id',
        'created_by',
        'assigned_by',
        'assigned_to',
        'title',
        'description',
        'status',
        'sla_status',
        'first_response_at',
        'response_notified_due_soon',
        'response_notified_overdue',
        'resolution_notified_due_soon',
        'resolution_notified_overdue',
        'resolved_at',
        'response_due_at',
        'resolution_due_at',
        'response_breached',
        'response_breached_at',
        'resolution_breached',
        'resolution_breached_at',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'response_breached_at' => 'datetime',
        'resolution_breached_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function scopeFilter($query, $filters)
    {
        return (new TicketFilter($query, $filters))->apply();
    }
}
