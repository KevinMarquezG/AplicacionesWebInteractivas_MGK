<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientTicketController extends Controller
{
    // Consultar los tickets del cliente
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with('game')
            ->latest()
            ->get();

        return view('client.tickets.index', compact('tickets'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $games = Game::where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('client.tickets.create', compact('games'));
    }

    // Guardar un nuevo ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'game_id' => [
                'nullable',
                Rule::exists('games', 'id')->where('status', 'published'),
            ],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(['normal', 'alta', 'urgente'])],
        ]);

        Ticket::create([
            'user_id' => auth()->id(),
            'game_id' => $validated['game_id'] ?? null,
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'status' => 'abierto',
        ]);

        return redirect()
            ->route('client.tickets.index')
            ->with('success', 'Tu ticket ha sido registrado correctamente.');
    }
    
    // Mostrar el detalle y conversación de un ticket
    public function show(Ticket $ticket)
    {
        // Verificar que el ticket pertenezca al cliente autenticado
        abort_unless($ticket->user_id === auth()->id(), 404);

        $ticket->load([
            'game',
            'replies' => function ($query) {
                $query->with('user')->orderBy('created_at', 'asc');
            },
        ]);

        return view('client.tickets.show', compact('ticket'));
    }

    // Registrar una respuesta del cliente
    public function reply(Request $request, Ticket $ticket)
    {
        // Verificar propiedad del ticket
        abort_unless($ticket->user_id === auth()->id(), 404);

        if ($ticket->status === 'resuelto') {
            return back()->with('error', 'Este ticket ya está resuelto.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Tu respuesta fue enviada correctamente.');
    }
}