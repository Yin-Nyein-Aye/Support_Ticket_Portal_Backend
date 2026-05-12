<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketComment $ticketComment): bool
    {
        if ($user->hasRole('agent')) {
            return true;
        }

        if ($ticketComment->author->organsation_id === null
        && $ticketComment->author->hasRole('agent')
        && $ticketComment->visibility === 'public'
        && $ticketComment->ticket->creator->organisation_id === $user->organisation_id)
        {
            return true;
        }

        return $user->organisation_id === $ticketComment->author->organisation_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('agent')) {
            return true;
        }

        return $user->organisation_id === $ticket->creator->organisation_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketComment $ticketComment): bool
    {
        return $user->id === $ticketComment->author->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketComment $ticketComment): bool
    {
        if ($user->hasRole('agent')) {
            return true;
        }

        return $user->id === $ticketComment->author->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketComment $ticketComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketComment $ticketComment): bool
    {
        return false;
    }
}
