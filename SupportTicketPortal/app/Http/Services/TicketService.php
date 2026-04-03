<?php

namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Http\Repositories\TicketRepository;
use App\Models\Ticket;
use App\Models\Priority;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketService extends BaseService
{
    public function __construct(TicketRepository $repository)
    {
        parent::__construct($repository);
    }

    // Store new ticket with initial comment, wrapped in a transaction
    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Default priority = High (id=3)
        $priority = Priority::find(1);
        if (!$priority) {
            throw new \Exception("Default High priority not found");
        }

        // Wrap ticket + first comment creation in a transaction
        return DB::transaction(function () use ($validated, $priority) {

            // Create ticket
            $ticket = $this->repository->create([
                'reference_number'       => 'TKT-' . strtoupper(Str::random(8)),
                'priority_id'            => $priority->id,
                'created_by'             => Auth::id(),
                'title'                  => $validated['title'],
                'description'            => $validated['description'],
                'status'                 => 'open',
                'sla_status'             => null,
                'response_due_at'        => now()->addHours($priority->response_hours),
                'resolution_due_at'      => now()->addHours($priority->resolution_hours),
                'assigned_by'            => null,
                'assigned_to'            => null,
                'first_response_at'      => null,
                'response_breached'      => false,
                'response_breached_at'   => null,
                'resolved_at'            => null,
                'resolution_breached'    => false,
                'resolution_breached_at' => null,
            ]);

            // Create first ticket comment using description
            $ticket->comments()->create([
                'author_id'  => Auth::id(),
                'body'       => $validated['description'],
                'visibility' => 'public',
            ]);

            event(new \App\Events\TicketCreated($ticket));

            return $ticket;
        });
    }

    public function updateClientTicket(int $ticketId, array $data)
    {
        $validator = Validator::make($data, [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Find ticket
        $ticket = $this->repository->find($ticketId);

        // Only allow update if agent hasn't responded yet
        if ($ticket->first_response_at) {
            throw new \Exception("Ticket cannot be updated after agent response.", 403);
        }

        // Update ticket title & description
        $ticket = $this->repository->update($ticketId, [
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ]);

        // Update first comment (assuming the first comment is the ticket description)
        $firstComment = $ticket->comments()->oldest()->first();
        if ($firstComment) {
            $firstComment->update([
                'body' => $validated['description']
            ]);
        }

        return $ticket;
    }

    public function assignTicket(int $ticketId, array $data)
    {
        // Validation
        $validator = Validator::make($data, [
            'priority_id' => ['required', 'exists:priorities,id'],
            'assigned_to' => ['required', 'exists:users,id'],
            'comment'     => ['nullable', 'string'],
            'type'        => ['nullable', 'in:public,internal'],
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Find ticket
        $ticket = $this->repository->find($ticketId);

        // Prevent re-assign after response
        if ($ticket->first_response_at) {
            throw new \Exception("Ticket already assigned.", 400);
        }

        // Get selected priority
        $priority = Priority::find($validated['priority_id']);

        return DB::transaction(function () use ($ticket, $validated, $priority) {
            $now = now();

            $responseBreached = false;
            $responseBreachedAt = null;

            if ($ticket->response_due_at && $now->gt($ticket->response_due_at)) {
                $responseBreached = true;
                $responseBreachedAt = $now;
            }

            $updatedTicket = $this->repository->update($ticket->id, [
                'priority_id'       => $priority->id,
                'assigned_by'       => Auth::id(),
                'assigned_to'       => $validated['assigned_to'],
                'status'            => 'in_progress',
                'first_response_at' => $now,
                'response_breached' => $responseBreached,
                'response_breached_at' => $responseBreachedAt,

                // calculate from first response
                'resolution_due_at' => $now->copy()->addHours($priority->resolution_hours),
                // REMOVE 'sla_status'
                'sla_status'             => 'on_track',
            ]);

            // Add comment
            if (!empty($validated['comment'])) {
                $updatedTicket->comments()->create([
                    'ticket_id'  => $updatedTicket->id,
                    'author_id'  => Auth::id(),
                    'body'       => $validated['comment'],
                    'visibility' => $validated['type'] ?? 'internal',
                ]);
            }
            event(new \App\Events\TicketAssigned($updatedTicket));
            return $updatedTicket;
        });
    }

    public function model(): string
    {
        return Ticket::class;
    }
}
