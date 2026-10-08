@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Mis Inscripciones</h1>

@if($inscripciones->isEmpty())
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
        Aún no estás inscrito en ningún torneo.
    </div>
@else
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Torneo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deporte/Juego</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Evento</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($inscripciones as $inscripcion)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $inscripcion->torneo->nombre }}</td>
                        <td class="px-6 py-4">{{ $inscripcion->torneo->juego_deporte }}</td>
                        <td class="px-6 py-4 text-sm">{{ $inscripcion->torneo->fecha_evento->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($inscripcion->torneo->fecha_evento->isFuture())
                                <form action="{{ route('inscripciones.cancelar', $inscripcion->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar tu inscripción y liberar el cupo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Cancelar Inscripción</button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">Concluido / Iniciado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection