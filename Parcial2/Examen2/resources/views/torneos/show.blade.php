@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6 max-w-3xl mx-auto">
    <div class="flex justify-between items-start">
        <div>
            <span class="text-sm font-semibold uppercase text-indigo-600">{{ $torneo->juego_deporte }}</span>
            <h1 class="text-3xl font-bold mt-1">{{ $torneo->nombre }}</h1>
            <p class="text-gray-500 text-sm mt-1">Fecha: {{ $torneo->fecha_evento->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            @if(!$torneo->activo || $torneo->fecha_evento->isPast() || $torneo->inscripciones_count >= $torneo->cupo)
                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold uppercase">Cerrado / Lleno</span>
            @else
                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold uppercase">Abierto</span>
            @endif
        </div>
    </div>

    <div class="mt-4 border-t pt-4">
        <h3 class="font-semibold text-gray-700">Descripción:</h3>
        <p class="text-gray-600 mt-1">{{ $torneo->descripcion ?: 'Sin descripción detallada.' }}</p>
        <p class="mt-2 text-sm text-gray-500">Cupo total: <strong>{{ $torneo->cupo }}</strong> | Inscritos: <strong>{{ $torneo->inscripciones_count }}</strong></p>
    </div>

    {{-- Botón de Inscripción --}}
    <div class="mt-6 border-t pt-4">
        @guest
            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Inicia sesión para inscribirte</a>
        @else
            @if(auth()->user()->role === 'jugador')
                @if($estaInscrito)
                    <div class="bg-blue-100 text-blue-700 p-3 rounded">Ya estás inscrito en este torneo.</div>
                @elseif(!$torneo->estaDisponible())
                    <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed font-medium">Inscripciones No Disponibles</button>
                @else
                    <form action="{{ route('torneos.inscribirse', $torneo->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded font-semibold">
                            Inscribirme al Torneo
                        </button>
                    </form>
                @endif
            @endif
        @endguest
    </div>

    {{-- Lista de Participantes --}}
    <div class="mt-8 border-t pt-4">
        <h2 class="text-lg font-bold mb-3">Participantes Registrados ({{ $torneo->participantes->count() }})</h2>
        @if($torneo->participantes->isEmpty())
            <p class="text-gray-500 text-sm">Aún no hay participantes inscritos.</p>
        @else
            <ul class="divide-y border rounded">
                @foreach($torneo->participantes as $participante)
                    <li class="p-3 text-sm flex justify-between">
                        <span>{{ $participante->name }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection