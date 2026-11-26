@extends('layouts.launcher')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div>
        <a href="{{ route('support.tickets') }}"
           class="text-sm text-cyan-400 hover:text-cyan-300">
            ← Volver al centro de soporte
        </a>

        <h1 class="text-3xl font-bold text-white mt-3">
            Ticket #{{ $ticket->id }}
        </h1>

        <p class="text-sm text-slate-400 mt-1">
            Conversación y seguimiento de la incidencia.
        </p>
    </div>

    {{-- Notificaciones --}}
    @if (session('success'))
        <div class="bg-emerald-950 text-emerald-300 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-950 text-rose-300 p-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    @error('message')
        <div class="bg-rose-950 text-rose-300 p-4 rounded-xl">
            {{ $message }}
        </div>
    @enderror

    {{-- Información del ticket --}}
    <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 space-y-4">

        <div class="flex flex-wrap justify-between items-start gap-3">
            <div>
                <h2 class="text-xl font-bold text-white">
                    {{ $ticket->subject }}
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    {{ $ticket->category }}

                    @if ($ticket->game)
                        · {{ $ticket->game->title }}
                    @endif
                </p>
            </div>

            <span class="text-xs font-bold px-3 py-2 rounded-lg
                {{ $ticket->status === 'resuelto'
                    ? 'bg-emerald-950 text-emerald-400'
                    : ($ticket->status === 'en_proceso'
                        ? 'bg-amber-950 text-amber-400'
                        : 'bg-rose-950 text-rose-400') }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div>
                <span class="text-slate-500">Cliente:</span>
                <span class="text-slate-200">
                    {{ $ticket->user?->name ?? 'Usuario eliminado' }}
                </span>
            </div>

            <div>
                <span class="text-slate-500">Correo:</span>
                <span class="text-slate-200">
                    {{ $ticket->user?->email ?? 'Sin correo' }}
                </span>
            </div>

            <div>
                <span class="text-slate-500">Prioridad:</span>
                <span class="text-slate-200">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </div>

            <div>
                <span class="text-slate-500">Fecha:</span>
                <span class="text-slate-200">
                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Historial --}}
    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden">

        <div class="p-5 border-b border-slate-800">
            <h2 class="text-lg font-bold text-white">
                Historial de conversación
            </h2>
        </div>

        <div class="p-5 space-y-5">

            {{-- Mensaje inicial del cliente --}}
            <div class="flex justify-start">
                <div class="max-w-[85%] w-full md:w-auto bg-slate-900 border border-slate-700 rounded-2xl p-4">

                    <div class="flex justify-between flex-wrap gap-2 mb-2">
                        <span class="text-sm font-bold text-cyan-300">
                            {{ $ticket->user?->name ?? 'Usuario eliminado' }}
                            (Cliente)
                        </span>

                        <span class="text-xs text-slate-500">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <p class="text-sm text-slate-200 whitespace-pre-line">{{ $ticket->message }}</p>
                </div>
            </div>

            {{-- Respuestas --}}
            @foreach ($ticket->replies as $reply)

                @php
                    $isClient = $reply->user_id == $ticket->user_id;
                @endphp

                <div class="flex {{ $isClient ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[85%] w-full md:w-auto rounded-2xl p-4 border
                        {{ $isClient
                            ? 'bg-slate-900 border-slate-700'
                            : 'bg-emerald-950/40 border-emerald-800/50' }}">

                        <div class="flex justify-between flex-wrap gap-2 mb-2">
                            <span class="text-sm font-bold
                                {{ $isClient ? 'text-cyan-300' : 'text-emerald-300' }}">
                                {{ $reply->user?->name ?? 'Usuario' }}
                                ({{ $isClient ? 'Cliente' : 'Soporte' }})
                            </span>

                            <span class="text-xs text-slate-500">
                                {{ $reply->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-200 whitespace-pre-line">{{ $reply->message }}</p>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

    {{-- Responder --}}
    @if ($ticket->status !== 'resuelto')

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 space-y-4">

            <h2 class="text-lg font-bold text-white">
                Responder al cliente
            </h2>

            <form method="POST"
                  action="{{ route('support.tickets.reply', $ticket) }}"
                  class="space-y-4">

                @csrf

                <textarea
                    name="message"
                    rows="5"
                    maxlength="5000"
                    required
                    placeholder="Escribe una respuesta para el cliente..."
                    class="w-full bg-[#090d15] border border-slate-700 rounded-xl p-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 resize-y"
                >{{ old('message') }}</textarea>

                <div class="flex justify-between items-center flex-wrap gap-3">

                    <p class="text-xs text-slate-500">
                        Al responder, el ticket pasará a estado En Proceso.
                    </p>

                    <button type="submit"
                            class="px-5 py-3 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition">
                        Enviar respuesta
                    </button>

                </div>

            </form>
        </div>

        {{-- Resolver ticket --}}
        <form method="POST"
              action="{{ route('support.tickets.resolve', $ticket->id) }}"
              onsubmit="return confirm('¿Deseas marcar este ticket como resuelto?');">

            @csrf

            <button type="submit"
                    class="px-5 py-3 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition">
                ✓ Resolver ticket
            </button>

        </form>

    @else

        <div class="bg-emerald-950/40 border border-emerald-800 rounded-xl p-5 text-center">
            <h3 class="font-bold text-emerald-400">
                ✓ Ticket resuelto
            </h3>

            <p class="text-sm text-emerald-300 mt-1">
                Esta conversación está cerrada y ya no admite nuevas respuestas.
            </p>
        </div>

    @endif

</div>
@endsection