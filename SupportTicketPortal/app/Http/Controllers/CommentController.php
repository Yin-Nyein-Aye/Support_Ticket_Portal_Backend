<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Http\Services\CommentService;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{
    public function __construct(CommentService $service)
    {
        parent::__construct($service, CommentResource::class);
    }

    // LIST comments for a ticket
    public function index($ticket_id)
    {
        $comments = TicketComment::where('ticket_id', $ticket_id)
            ->with(['author', 'replies'])
            ->oldest()
            ->get();

        return CommentResource::collection($comments);
    }

    // STORE a new comment or reply
    public function store(Request $request)
    {
        $user = Auth::user();
        $ticket_id = $request->route('ticket_id');

        $allowedVisibility = $user->hasRole('agent') ? ['public', 'private'] : ['public'];

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:100'],
            'visibility' => ['required', 'in:' . implode(',', $allowedVisibility)],
            'parent_comment_id' => ['nullable', 'exists:ticket_comments,id'],
        ], [
            'body.required' => 'Comment body cannot be empty!',
            'body.max' => 'Comment body cannot exceed 100 characters!',
            'visibility.in' => 'You are not allowed to set this visibility!',
        ]);

        $validated['ticket_id'] = $ticket_id;
        $validated['author_id'] = $user->id;

        $comment = $this->service->create($validated);

        return new CommentResource($comment);
    }
}
