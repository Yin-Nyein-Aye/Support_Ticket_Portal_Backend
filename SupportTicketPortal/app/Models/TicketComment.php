<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'author_id',
        'body',
        'visibility',
        'parent_comment_id',
    ];

    // The user who made the comment
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // replies to this comment
    public function replies()
    {
        return $this->hasMany(TicketComment::class, 'parent_comment_id')
            ->with('replies');
    }

    // parent comment
    public function parent()
    {
        return $this->belongsTo(TicketComment::class, 'parent_comment_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
