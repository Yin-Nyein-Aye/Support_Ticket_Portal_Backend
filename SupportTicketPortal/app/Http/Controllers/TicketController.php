<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Http\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TicketController extends BaseController
{
    public function __construct(TicketService $service)
    {
        parent::__construct($service, TicketResource::class);
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'priority_id',
            'assigned_to',
            'keyword',
            'date_from',
            'date_to',
            'organisation_id'
        ]);

        $query = Ticket::query()
            ->filter($filters)
            ->with(['creator', 'assignee', 'priority']);

        $user = Auth::user();

        //  CLIENT → only tickets from same organisation
        if ($user->hasRole('client')) {
            $query->whereIn('created_by', function ($q) use ($user) {
                $q->select('id')
                    ->from('users')
                    ->where('organisation_id', $user->organisation_id);
            });
        }

        $tickets = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $tickets
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => [
                    'required',
                    'string',
                    'min:5',
                    'max:255',
                    'regex:/[a-zA-Z]/' //  must contain at least one letter
                ],
                'description' => [
                    'required',
                    'string',
                    'min:5',
                    'regex:/[a-zA-Z]/' //  must contain at least one letter
                ],
            ]);

            // pass validated data only
            $ticket = $this->service->store($validated);

            return new TicketResource($ticket);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        }
    }

    public function clientUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => [
                    'sometimes',
                    'required',
                    'string',
                    'min:5',
                    'max:255',
                    'regex:/[a-zA-Z]/'
                ],
                'description' => [
                    'sometimes',
                    'required',
                    'string',
                    'min:5',
                    'regex:/[a-zA-Z]/'
                ],
            ], [
                'title.regex' => 'Title must contain at least one letter.',
                'description.regex' => 'Description must contain meaningful text (letters).',
            ]);

            $ticket = $this->service->updateClientTicket($id, $validated);

            return new TicketResource($ticket);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $ticket = $this->service->assignTicket($id, $request->all());
            return new TicketResource($ticket);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
