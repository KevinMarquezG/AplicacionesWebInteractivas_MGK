<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Ticket;

class SupportController extends Controller
{
    public function tickets(Request $request)
    {
        $filterStatus = $request->query('status', 'all');
        $filterPriority = $request->query('priority', 'all');

        // Videojuegos pendientes de moderación.
        $pendingGames = Game::where('status', 'pending_approval')
            ->with(['developer', 'categories'])
            ->latest()
            ->get();

        // Tickets reales registrados por los clientes.
        $ticketQuery = Ticket::with(['user', 'game', 'replies.user'])
            ->latest();

        if ($filterStatus !== 'all') {
            $ticketQuery->where('status', $filterStatus);
        }

        if ($filterPriority !== 'all') {
            $ticketQuery->where('priority', $filterPriority);
        }

        $tickets = $ticketQuery->get();

        // Métricas reales.
        $openCount = Ticket::where('status', 'abierto')->count();

        $inProgressCount = Ticket::where('status', 'en_proceso')->count();

        $resolvedToday = Ticket::where('status', 'resuelto')
            ->whereDate('updated_at', today())
            ->count();

        // Tiempo promedio de primera respuesta.
        $ticketsWithReplies = Ticket::whereHas('replies')
            ->with(['replies' => function ($query) {
                $query->orderBy('created_at');
            }])
            ->get();

        $responseTimes = $ticketsWithReplies->map(function ($ticket) {
            $firstReply = $ticket->replies->first();

            return $firstReply
                ? $ticket->created_at->diffInSeconds($firstReply->created_at)
                : null;
        })->filter();

        if ($responseTimes->isNotEmpty()) {
            $averageSeconds = $responseTimes->avg();

            if ($averageSeconds < 3600) {
                $avgResponseTime = round($averageSeconds / 60) . ' min';
            } else {
                $avgResponseTime = round($averageSeconds / 3600, 1) . ' h';
            }
        } else {
            $avgResponseTime = 'Sin respuestas';
        }

        return view('support.tickets.index', compact(
            'tickets',
            'pendingGames',
            'filterStatus',
            'filterPriority',
            'openCount',
            'inProgressCount',
            'resolvedToday',
            'avgResponseTime'
        ));
    }

    public function approveGame(Game $game)
    {
        if ($game->status !== 'pending_approval') {
            return back()->with('error', 'Este videojuego ya no está pendiente de revisión.');
        }

        $game->update(['status' => 'published']);

        return back()->with('success', "El videojuego '{$game->title}' fue aprobado y publicado.");
    }

    public function rejectGame(Game $game)
    {
        if ($game->status !== 'pending_approval') {
            return back()->with('error', 'Este videojuego ya no está pendiente de revisión.');
        }

        $game->update(['status' => 'rejected']);

        return back()->with('success', "El videojuego '{$game->title}' fue rechazado.");
    }

    public function resolve(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status === 'resuelto') {
            return back()->with('error', 'Este ticket ya se encuentra resuelto.');
        }

        $ticket->update([
            'status' => 'resuelto',
        ]);

        return back()->with('success', "Ticket #{$ticket->id} marcado como resuelto.");
    }

    // Mostrar el detalle y conversación de un ticket
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'game', 'replies.user']);

        return view('support.tickets.show', compact('ticket'));
    }

    // Registrar una respuesta del personal de soporte
    public function reply(Request $request, Ticket $ticket)
    {
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

        $ticket->update([
            'status' => 'en_proceso',
        ]);

        return back()->with('success', 'La respuesta fue enviada correctamente.');
    }
}
