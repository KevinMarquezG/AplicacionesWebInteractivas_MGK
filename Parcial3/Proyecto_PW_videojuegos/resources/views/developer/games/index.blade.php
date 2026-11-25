@extends('layouts.launcher')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado del Portal -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-md bg-indigo-950 text-indigo-400 border border-indigo-800/60 tracking-wider">
                    Panel de Desarrollador
                </span>
                <span class="text-xs text-slate-400 font-medium">Estudio: {{ Auth::user()->name }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1" style="font-family: 'Rajdhani', sans-serif;">
                GESTIÓN DE VIDEOJUEGOS
            </h1>
            <p class="text-xs text-slate-400">Publica nuevos títulos, administra versiones y monitorea el rendimiento de tus juegos.</p>
        </div>

        <div>
            <a href="{{ route('developer.games.create') }}" 
               class="inline-flex items-center space-x-2 px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 text-white shadow-lg shadow-cyan-500/25 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Subir Nuevo Videojuego</span>
            </a>
        </div>
    </div>

    <!-- Indicadores Rápidos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Títulos Registrados</span>
            <h3 class="text-3xl font-extrabold text-white mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $totalGames }}</h3>
            <p class="text-[11px] text-slate-500 mt-2">{{ $publishedGames }} títulos publicados y activos</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Jugadores en Biblioteca</span>
            <h3 class="text-3xl font-extrabold text-indigo-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ number_format($totalPlayers) }}</h3>
            <p class="text-[11px] text-slate-500 mt-2">Copias añadidas por usuarios</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Ingresos Brutos</span>
            <h3 class="text-3xl font-extrabold text-emerald-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">${{ number_format($estimatedRevenue, 2) }}</h3>
            <p class="text-[11px] text-slate-500 mt-2">Ventas generadas en la tienda</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Estado de Cuenta</span>
            <h3 class="text-xl font-bold text-cyan-300 mt-1" style="font-family: 'Rajdhani', sans-serif;">VERIFICADO</h3>
            <p class="text-[11px] text-slate-500 mt-2">Publicación directa habilitada</p>
        </div>
    </div>

    <!-- Tabla de Videojuegos -->
    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-gaming text-lg font-bold text-white tracking-wide" style="font-family: 'Rajdhani', sans-serif;">
                TUS LANZAMIENTOS
            </h3>
            <span class="text-xs text-slate-400">{{ $games->count() }} juegos en tu catálogo</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-[#090d15] text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-6">Videojuego</th>
                        <th class="py-3.5 px-4">Precio</th>
                        <th class="py-3.5 px-4">Versión</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4">Jugadores</th>
                        <th class="py-3.5 px-4">Fecha</th>
                        <th class="py-3.5 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($games as $game)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $game->cover_image }}" 
                                         alt="{{ $game->title }}" 
                                         class="w-12 h-14 object-cover rounded-lg border border-slate-700/60 shadow-md flex-shrink-0">
                                    <div>
                                        <p class="font-bold text-sm text-white">{{ $game->title }}</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach ($game->categories as $category)
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-cyan-400 font-medium">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-white">
                                {{ $game->isFree() ? 'GRATIS' : '$' . number_format($game->price, 2) }}
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-400">
                                v{{ $game->version }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($game->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-400"></span> Publicado
                                    </span>
                                @elseif ($game->status === 'pending_approval')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-950 text-amber-400 border border-amber-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-400"></span> En Revisión
                                    </span>
                                @elseif ($game->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-950 text-rose-400 border border-rose-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-400"></span> Rechazado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-slate-400"></span> Borrador
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-200">
                                {{ number_format($game->owners_count) }}
                            </td>
                            <td class="py-4 px-4 text-slate-400">
                                {{ $game->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('developer.games.edit', $game) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-400 text-xs font-semibold border border-slate-700 transition">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('developer.games.destroy', $game) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este videojuego?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-500">
                                <p class="text-sm font-semibold text-slate-300">Aún no has subido ningún videojuego.</p>
                                <a href="{{ route('developer.games.create') }}" 
                                   class="inline-block mt-4 px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition">
                                    Subir Mi Primer Videojuego
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection