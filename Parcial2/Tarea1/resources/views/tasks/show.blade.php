@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex justify-between items-start gap-4 mb-4 pb-4 border-b border-slate-100">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $tarea->titulo }}</h1>
            <p class="text-xs text-slate-400 mt-1">Creada el {{ $tarea->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tasks.edit', $tarea) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">Editar</a>
            <form method="POST" action="{{ route('tasks.destroy', $tarea) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
            <span class="text-xs text-slate-400 block">Estado</span>
            <span class="text-sm font-semibold text-slate-700">{{ $estados[$tarea->estado] ?? $tarea->estado }}</span>
        </div>
        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
            <span class="text-xs text-slate-400 block">Prioridad</span>
            <span class="text-sm font-semibold text-slate-700">{{ $prioridades[$tarea->prioridad] ?? $tarea->prioridad }}</span>
        </div>
        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
            <span class="text-xs text-slate-400 block">Vencimiento</span>
            <span class="text-sm font-semibold {{ $tarea->estaVencida() ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                {{ $tarea->vencimiento ? $tarea->vencimiento->format('d/m/Y') : 'Sin fecha' }}
            </span>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Descripción</h2>
        <div class="bg-slate-50 p-4 rounded-lg text-sm text-slate-700 whitespace-pre-line border border-slate-100">
            {{ $tarea->descripcion ?: 'Sin descripción detallada.' }}
        </div>
    </div>

    <div>
        <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">← Volver al tablero</a>
    </div>
</div>
@endsection