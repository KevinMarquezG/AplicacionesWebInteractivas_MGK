@extends('layouts.launcher')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">
                Mis tickets
            </h1>
            <p class="text-sm text-slate-400 mt-2">
                Consulta el estado de tus solicitudes de soporte.
            </p>
        </div>

        <a href="{{ route('client.tickets.create') }}"
           class="px-4 py-3 rounded-lg bg-cyan-500 text-slate-950 font-bold">
            Crear ticket
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-950 text-emerald-300 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden">

        @forelse ($tickets as $ticket)
            <div class="p-5 border-b border-slate-800 space-y-2">

                <div class="flex justify-between gap-3">
                    <span class="text-cyan-400 font-mono text-sm">
                        #{{ $ticket->id }}
                    </span>

                    <span class="text-xs font-bold
                        {{ $ticket->status === 'resuelto' ? 'text-emerald-400' : ($ticket->status === 'en_proceso' ? 'text-amber-400' : 'text-rose-400') }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>

                <h3 class="text-white font-bold">
                    {{ $ticket->subject }}
                </h3>

                <p class="text-sm text-slate-400">
                    {{ $ticket->category }}
                    @if ($ticket->game)
                        · {{ $ticket->game->title }}
                    @endif
                </p>

                <p class="text-sm text-slate-300">
                    {{ $ticket->message }}
                </p>

                <div class="text-xs text-slate-500">
                    Prioridad: {{ ucfirst($ticket->priority) }}
                    · {{ $ticket->created_at->diffForHumans() }}
                </div>
                <a href="{{ route('client.tickets.show', $ticket) }}"
                class="inline-block text-sm font-bold text-cyan-400 hover:text-cyan-300 mt-2">
                    Ver conversación →
                </a>
            </div>
        @empty
            <div class="p-10 text-center text-slate-400">
                Todavía no has creado ningún ticket.
            </div>
        @endforelse

    </div>
</div>
@endsection
