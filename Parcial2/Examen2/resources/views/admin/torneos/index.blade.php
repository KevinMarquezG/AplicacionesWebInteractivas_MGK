@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Gestión de Torneos (Admin)</h1>
    <a href="{{ route('admin.torneos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow">
        + Nuevo Torneo
    </a>
</div>

@if($torneos->isEmpty())
    <div class="bg-gray-100 border border-gray-300 p-6 rounded text-center text-gray-600">
        No hay torneos registrados actualmente. Presiona en "+ Nuevo Torneo" para crear uno.
    </div>
@else
    <div class="bg-white shadow rounded-lg overflow-hidden border">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Torneo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Juego/Deporte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Evento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscritos / Cupo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($torneos as $torneo)
                    <tr>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $torneo->nombre }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $torneo->juego_deporte }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $torneo->fecha_evento->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-semibold">{{ $torneo->inscripciones_count }}</span> / {{ $torneo->cupo }}
                        </td>
                        <td class="px-6 py-4">
                            @if($torneo->activo && $torneo->fecha_evento->isFuture() && $torneo->inscripciones_count < $torneo->cupo)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold uppercase">Abierto</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold uppercase">Cerrado / Lleno</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.torneos.participantes', $torneo->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Participantes</a>
                            <a href="{{ route('admin.torneos.edit', $torneo->id) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">Editar</a>
                            <form action="{{ route('admin.torneos.destroy', $torneo->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este torneo? Todas las inscripciones asociadas se borrarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection