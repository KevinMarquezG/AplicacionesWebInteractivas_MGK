@extends('layouts.launcher')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-slate-800">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-md bg-cyan-950 text-cyan-400 border border-cyan-800/60 tracking-wider">
                    Helpdesk & Moderación
                </span>
                <span class="text-xs text-slate-400">Atención a Jugadores y Creadores</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1" style="font-family: 'Rajdhani', sans-serif;">
                CENTRO DE TICKETS Y SOPORTE
            </h1>
        </div>
        <span class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Cola de Atención: <b>En Línea</b></span>
        </span>
    </div>

    <!-- Métricas SLA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Tickets Abiertos</span>
            <h3 class="text-3xl font-extrabold text-white mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $openCount }}</h3>
            <p class="text-[11px] text-rose-400 mt-2">Requieren respuesta</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">En Diagnóstico</span>
            <h3 class="text-3xl font-extrabold text-amber-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $inProgressCount }}</h3>
            <p class="text-[11px] text-slate-400 mt-2">En seguimiento</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Resueltos Hoy</span>
            <h3 class="text-3xl font-extrabold text-emerald-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $resolvedToday }}</h3>
            <p class="text-[11px] text-slate-400 mt-2">Cerrados satisfactoriamente</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Tiempo Promedio</span>
            <h3 class="text-3xl font-extrabold text-cyan-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $avgResponseTime }}</h3>
            <p class="text-[11px] text-slate-400 mt-2">Objetivo SLA: &lt; 45 min</p>
        </div>
    </div>

    <!-- Cola de Moderación de Videojuegos -->
    <div class="bg-[#111726] border border-amber-800/50 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-amber-300" style="font-family: 'Rajdhani', sans-serif;">VIDEOJUEGOS PENDIENTES DE APROBACIÓN</h3>
                <p class="text-xs text-slate-400 mt-1">Revisa las publicaciones enviadas por los desarrolladores.</p>
            </div>
            <span class="px-3 py-1 rounded-lg bg-amber-950/50 text-amber-300 text-xs font-bold">{{ $pendingGames->count() }} pendientes</span>
        </div>

        <div class="divide-y divide-slate-800/80">
            @forelse ($pendingGames as $game)
                <div class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <img src="{{ $game->cover_image }}" alt="Portada de {{ $game->title }}" class="w-14 h-16 object-cover rounded-lg border border-slate-700">

                        <div class="min-w-0">
                            <h4 class="font-bold text-white">
                                {{ $game->title }}
                                <span class="text-xs text-cyan-400 font-mono">v{{ $game->version }}</span>
                            </h4>

                            <p class="text-xs text-slate-400 mt-1">
                                Desarrollador: {{ $game->developer->name ?? 'No disponible' }}
                                ({{ $game->developer->email ?? 'N/A' }})
                            </p>

                            <p class="text-xs text-slate-300 mt-2">{{ $game->short_description }}</p>

                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($game->categories as $category)
                                    <span class="px-2 py-1 rounded-md bg-slate-800 text-xs text-slate-300">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <form method="POST" action="{{ route('support.games.approve', $game) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950">
                                Aprobar y publicar
                            </button>
                        </form>

                        <form method="POST" action="{{ route('support.games.reject', $game) }}" onsubmit="return confirm('¿Deseas rechazar esta publicación?');">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg text-xs font-bold bg-rose-950 hover:bg-rose-900 text-rose-300 border border-rose-800">
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-sm text-slate-500">
                    No hay videojuegos pendientes de aprobación.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Lista de Incidencias -->
    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">

        <!-- Filtros -->
        <div class="p-5 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div class="flex items-center space-x-2 overflow-x-auto">
                <span class="text-xs text-slate-400 font-semibold uppercase mr-2">Estado:</span>

                <a href="{{ route('support.tickets', ['status' => 'all', 'priority' => $filterPriority]) }}"
                   class="px-3 py-1 rounded-lg text-xs font-semibold {{ $filterStatus === 'all' ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Todos
                </a>

                <a href="{{ route('support.tickets', ['status' => 'abierto', 'priority' => $filterPriority]) }}"
                   class="px-3 py-1 rounded-lg text-xs font-semibold {{ $filterStatus === 'abierto' ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Abiertos
                </a>

                <a href="{{ route('support.tickets', ['status' => 'en_proceso', 'priority' => $filterPriority]) }}"
                   class="px-3 py-1 rounded-lg text-xs font-semibold {{ $filterStatus === 'en_proceso' ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    En Proceso
                </a>

                <a href="{{ route('support.tickets', ['status' => 'resuelto', 'priority' => $filterPriority]) }}"
                   class="px-3 py-1 rounded-lg text-xs font-semibold {{ $filterStatus === 'resuelto' ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Resueltos
                </a>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-xs text-slate-400 font-semibold uppercase mr-2">Prioridad:</span>

                <a href="{{ route('support.tickets', ['status' => $filterStatus, 'priority' => 'all']) }}"
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $filterPriority === 'all' ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Todas
                </a>

                <a href="{{ route('support.tickets', ['status' => $filterStatus, 'priority' => 'urgente']) }}"
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $filterPriority === 'urgente' ? 'bg-rose-500 text-white' : 'bg-slate-800 text-rose-400 hover:bg-slate-700' }}">
                    Urgente
                </a>

                <a href="{{ route('support.tickets', ['status' => $filterStatus, 'priority' => 'alta']) }}"
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $filterPriority === 'alta' ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-amber-400 hover:bg-slate-700' }}">
                    Alta
                </a>

                <a href="{{ route('support.tickets', ['status' => $filterStatus, 'priority' => 'normal']) }}"
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $filterPriority === 'normal' ? 'bg-slate-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                    Normal
                </a>
            </div>
        </div>

        <!-- Tickets -->
        <div class="divide-y divide-slate-800/80">
            @forelse ($tickets as $t)
                <div class="p-5 hover:bg-slate-900/40 transition space-y-3">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono text-xs font-bold text-cyan-400 bg-slate-900 px-2.5 py-1 rounded-md border border-slate-700">
                                #{{ $t->id }}
                            </span>

                            <span class="text-xs px-2 py-0.5 rounded font-semibold bg-indigo-950 text-indigo-300 border border-indigo-800/50">
                                {{ $t->category }}
                            </span>

                            @if ($t->game)
                                <span class="text-xs text-slate-400">
                                    Juego: <b class="text-slate-200">{{ $t->game->title }}</b>
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-3 text-xs">
                            @if ($t->priority === 'urgente')
                                <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] bg-rose-950 text-rose-400 border border-rose-800">
                                    Urgente
                                </span>
                            @elseif ($t->priority === 'alta')
                                <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] bg-amber-950 text-amber-400 border border-amber-800">
                                    Alta
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] bg-slate-800 text-slate-400">
                                    Normal
                                </span>
                            @endif

                            <span class="text-slate-500">{{ $t->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base font-bold text-white">{{ $t->subject }}</h4>

                        <p class="text-xs text-slate-300 mt-1 leading-relaxed bg-[#090d15] p-3 rounded-xl border border-slate-800">
                            {{ $t->message }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div class="text-xs text-slate-400">
                            Usuario:
                            <span class="text-slate-200 font-semibold">
                                {{ $t->user?->name ?? 'Usuario eliminado' }}
                            </span>
                            ({{ $t->user?->email ?? 'Sin correo' }})
                        </div>

                        <div>
                            @if ($t->status !== 'resuelto')
                                <form method="POST" action="{{ route('support.tickets.resolve', $t->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-1.5 rounded-lg text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950 transition">
                                        Resolver Ticket
                                    </button>
                                    <a href="{{ route('support.tickets.show', $t) }}"
   class="inline-block px-4 py-1.5 rounded-lg text-xs font-bold bg-cyan-950 hover:bg-cyan-900 text-cyan-300 border border-cyan-800 transition">
    Ver conversación
</a>
                                </form>
                            @else
                                <span class="text-xs font-bold text-emerald-400">✓ Resuelto</span>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="py-12 text-center text-slate-500 text-xs">
                    No hay incidencias con los filtros seleccionados.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
