@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/70 via-white to-transparent pt-10 pb-14 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mb-4">
            🔥 Competencias Abiertas
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight max-w-3xl mx-auto">
            Compite, demuestra tu nivel y <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">gana torneos</span>
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
            Explora las próximas fechas de fútbol, básquetbol, eSports y más. Regístrate en segundos para asegurar tu plaza antes de que se agoten los cupos.
        </p>

        @guest
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition">
                    Crear cuenta de Jugador
                </a>
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold border border-slate-200 rounded-xl transition">
                    Ya tengo cuenta
                </a>
            </div>
        @endguest
    </div>
</section>

<!-- Listado de Torneos -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Torneos Disponibles</h2>
            <p class="text-sm text-slate-500">Solo se muestran torneos abiertos, con cupo libre y fechas futuras.</p>
        </div>
        <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200">
            {{ $torneos->count() }} {{ $torneos->count() === 1 ? 'disponible' : 'disponibles' }}
        </span>
    </div>

    @if($torneos->isEmpty())
        <!-- Empty State Visual si la BD no tiene torneos aptos -->
        <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center max-w-xl mx-auto my-6 shadow-sm">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                🗓️
            </div>
            <h3 class="text-xl font-bold text-slate-800">No hay torneos activos en este momento</h3>
            <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                Los organizadores aún no publican nuevas fechas o todos los cupos fueron completados.
            </p>

            @auth
                @if(auth()->user()->esAdmin())
                    <div class="mt-6">
                        <a href="{{ route('admin.torneos.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition shadow">
                            + Crear el primer torneo
                        </a>
                    </div>
                @endif
            @else
                <div class="mt-6 text-xs text-slate-400">
                    ¿Eres administrador? <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Inicia sesión</a> para publicar un torneo.
                </div>
            @endauth
        </div>
    @else
        <!-- Grid de Tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($torneos as $torneo)
                @php
                    $ocupados = $torneo->inscripciones_count;
                    $total = $torneo->cupo;
                    $disponibles = $total - $ocupados;
                    $porcentaje = ($total > 0) ? ($ocupados / $total) * 100 : 0;
                @endphp

                <div class="group bg-white rounded-2xl border border-slate-200 hover:border-indigo-300 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div class="p-6">
                        <!-- Header de la tarjeta -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-colors">
                                🎮 {{ $torneo->juego_deporte }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Abierto
                            </span>
                        </div>

                        <!-- Título y Descripción -->
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $torneo->nombre }}
                        </h3>
                        <p class="text-slate-500 text-sm mt-2 line-clamp-2 leading-relaxed">
                            {{ $torneo->descripcion ?: 'Sin descripción adicional disponible para este torneo.' }}
                        </p>

                        <!-- Datos del Evento -->
                        <div class="mt-5 space-y-2.5 text-sm text-slate-600 border-t border-slate-100 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400">📅</span>
                                <span class="font-medium text-slate-700">
                                    {{ $torneo->fecha_evento->isoFormat('D [de] MMMM, YYYY') }}
                                </span>
                                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono">
                                    {{ $torneo->fecha_evento->format('H:i') }} hrs
                                </span>
                            </div>

                            <!-- Barra de Cupo -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-500">Lugares disponibles</span>
                                    <span class="font-bold text-slate-800">{{ $disponibles }} de {{ $total }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer con Botón -->
                    <div class="p-6 pt-0">
                        <a href="{{ route('torneos.show', $torneo->id) }}" class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all duration-200 shadow-sm group-hover:shadow">
                            Ver detalles e inscribirse
                            <span class="text-xs">→</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection