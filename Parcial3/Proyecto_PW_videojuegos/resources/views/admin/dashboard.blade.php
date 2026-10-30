@extends('layouts.launcher')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-slate-800">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-md bg-rose-950 text-rose-400 border border-rose-800/60 tracking-wider">
                    Super Admin Console
                </span>
                <span class="text-xs text-slate-400">Control Central del Ecosistema</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1" style="font-family: 'Rajdhani', sans-serif;">
                ADMINISTRACIÓN DE LA PLATAFORMA
            </h1>
        </div>
        <span class="px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300">
            Estado: <span class="text-emerald-400 font-bold">Servidor Operacional</span>
        </span>
    </div>

    <!-- Indicadores -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Total Usuarios</span>
            <h3 class="text-3xl font-extrabold text-white mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $totalUsers }}</h3>
            <p class="text-[11px] text-slate-500 mt-2">Cuentas registradas</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Catálogo Total</span>
            <h3 class="text-3xl font-extrabold text-white mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $totalGames }}</h3>
            <p class="text-[11px] text-slate-500 mt-2">{{ $publishedGamesCount }} juegos activos en tienda</p>
        </div>

        <div class="bg-[#111726] border {{ $pendingGamesCount > 0 ? 'border-amber-500/50 bg-amber-950/10' : 'border-slate-800' }} rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-amber-400 tracking-wider">Pendientes de Visto Bueno</span>
            <h3 class="text-3xl font-extrabold text-amber-300 mt-1" style="font-family: 'Rajdhani', sans-serif;">{{ $pendingGamesCount }}</h3>
            <p class="text-[11px] text-slate-400 mt-2">Subidos por desarrolladores</p>
        </div>

        <div class="bg-[#111726] border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Seguridad y Acceso</span>
            <h3 class="text-xl font-bold text-emerald-400 mt-1" style="font-family: 'Rajdhani', sans-serif;">PROTEGIDO</h3>
            <p class="text-[11px] text-slate-500 mt-2">Middleware por roles activo</p>
        </div>
    </div>

    <!-- Cola de Moderación de Videojuegos -->
    @if ($pendingGames->isNotEmpty())
        <div class="bg-[#111726] border border-amber-500/40 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 bg-amber-950/20 border-b border-amber-800/40 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <h3 class="font-gaming text-lg font-bold text-amber-300 tracking-wide" style="font-family: 'Rajdhani', sans-serif;">
                        COLA DE MODERACIÓN: VIDEOJUEGOS PENDIENTES ({{ $pendingGames->count() }})
                    </h3>
                </div>
            </div>

            <div class="divide-y divide-slate-800">
                @foreach ($pendingGames as $game)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-900/40 transition">
                        <div class="flex items-start space-x-4">
                            <img src="{{ $game->cover_image }}" class="w-14 h-18 object-cover rounded-xl border border-slate-700 flex-shrink-0">
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h4 class="text-base font-bold text-white">{{ $game->title }}</h4>
                                    <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-800 text-cyan-400">v{{ $game->version }}</span>
                                    <span class="text-xs font-bold text-emerald-400">{{ $game->isFree() ? 'GRATIS' : '$' . number_format($game->price, 2) }}</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ $game->short_description }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Estudio: <b class="text-slate-300">{{ $game->developer->name ?? 'Indie' }}</b> ({{ $game->developer->email ?? 'N/A' }})
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2.5 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.games.approve', $game) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-500 hover:bg-emerald-400 text-slate-950 transition">
                                    Aprobar y Publicar
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.games.reject', $game) }}">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-rose-950 hover:bg-rose-900 text-rose-300 border border-rose-800/60 transition">
                                    Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Gestión de Usuarios y Roles -->
    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="font-gaming text-lg font-bold text-white tracking-wide" style="font-family: 'Rajdhani', sans-serif;">
                    GESTIÓN DE USUARIOS Y ROLES
                </h3>
            </div>

            <!-- Filtros por Rol -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-3 py-1 rounded-lg text-xs font-semibold {{ empty($selectedRole) ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Todos
                </a>
                @foreach ($roles as $r)
                    <a href="{{ route('admin.dashboard', ['role' => $r->slug]) }}" 
                       class="px-3 py-1 rounded-lg text-xs font-semibold {{ $selectedRole === $r->slug ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                        {{ $r->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-[#090d15] text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-6">Usuario</th>
                        <th class="py-3.5 px-4">Rol Asignado</th>
                        <th class="py-3.5 px-4">Billetera</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4">Fecha</th>
                        <th class="py-3.5 px-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($users as $u)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-cyan-400 text-xs">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-white">{{ $u->name }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline">
                                    @csrf
                                    <select name="role_id" onchange="this.form.submit()" 
                                            class="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-2.5 py-1 focus:outline-none focus:border-cyan-500">
                                        @foreach ($roles as $roleOption)
                                            <option value="{{ $roleOption->id }}" {{ $u->role_id === $roleOption->id ? 'selected' : '' }}>
                                                {{ $roleOption->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="py-4 px-4 font-semibold text-emerald-400">
                                ${{ number_format($u->wallet_balance, 2) }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800/50">
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-950 text-rose-400 border border-rose-800/50">
                                        Suspendida
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-400">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if ($u->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1 rounded-lg text-xs font-semibold {{ $u->is_active ? 'bg-rose-950 hover:bg-rose-900 text-rose-300 border border-rose-800/50' : 'bg-emerald-950 hover:bg-emerald-900 text-emerald-300 border border-emerald-800/50' }} transition">
                                            {{ $u->is_active ? 'Suspender' : 'Reactivar' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500 italic">Sesión actual</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection