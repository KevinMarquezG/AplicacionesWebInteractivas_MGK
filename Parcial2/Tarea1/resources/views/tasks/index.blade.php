@extends('layouts.app')

@section('content')
<!-- Barra de filtros -->
<form method="GET" action="{{ route('tasks.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Buscar</label>
        <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Título..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Prioridad</label>
        <select name="prioridad" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">Todas</option>
            @foreach ($prioridades as $clave => $nombre)
                <option value="{{ $clave }}" {{ ($filtros['prioridad'] ?? '') === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Estado</label>
        <select name="estado" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">Todos</option>
            @foreach ($estados as $clave => $nombre)
                <option value="{{ $clave }}" {{ ($filtros['estado'] ?? '') === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-slate-900 transition">Filtrar</button>
        <a href="{{ route('tasks.index') }}" class="bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg hover:bg-slate-300 transition">Limpiar</a>
    </div>
</form>

<!-- Tablero por columnas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach ($estados as $claveEstado => $nombreEstado)
        @php
            $tareasColumna = $tareasPorEstado->get($claveEstado, collect());
        @endphp
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col min-h-[500px]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-slate-700">{{ $nombreEstado }}</h2>
                <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full font-semibold">{{ $tareasColumna->count() }}</span>
            </div>

            <div class="flex flex-col gap-3 flex-1">
                @forelse ($tareasColumna as $tarea)
                    <div class="bg-white border {{ $tarea->estaVencida() ? 'border-rose-400' : 'border-slate-200' }} rounded-lg p-4 shadow-sm hover:shadow transition">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <a href="{{ route('tasks.show', $tarea) }}" class="font-semibold text-slate-800 hover:text-indigo-600 line-clamp-2">
                                {{ $tarea->titulo }}
                            </a>
                            <span class="text-xs px-2 py-0.5 rounded font-semibold 
                                @if($tarea->prioridad === 'alta') bg-rose-100 text-rose-700
                                @elseif($tarea->prioridad === 'media') bg-amber-100 text-amber-700
                                @else bg-slate-100 text-slate-600 @endif">
                                {{ $prioridades[$tarea->prioridad] }}
                            </span>
                        </div>

                        @if ($tarea->descripcion)
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $tarea->descripcion }}</p>
                        @endif

                        <div class="flex justify-between items-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                            <span>
                                @if ($tarea->vencimiento)
                                    <span class="{{ $tarea->estaVencida() ? 'text-rose-600 font-bold' : '' }}">
                                        📅 {{ $tarea->vencimiento->format('d/m/Y') }}
                                    </span>
                                @endif
                            </span>

                            <!-- Cambio rápido de estado -->
                            <form method="POST" action="{{ route('tasks.change-status', $tarea) }}">
                                @csrf
                                @method('PATCH')
                                <select name="estado" onchange="this.form.submit()" class="text-xs border border-slate-200 rounded px-1.5 py-0.5 bg-slate-50 text-slate-700 outline-none">
                                    @foreach ($estados as $subClave => $subNombre)
                                        <option value="{{ $subClave }}" {{ $tarea->estado === $subClave ? 'selected' : '' }}>
                                            {{ $subNombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400 border border-dashed border-slate-200 rounded-lg">
                        Sin tareas en este estado
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection