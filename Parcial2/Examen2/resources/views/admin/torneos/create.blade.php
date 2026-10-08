@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Crear Nuevo Torneo</h2>

    <form action="{{ route('admin.torneos.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Nombre del Torneo *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full mt-1 border rounded p-2 @error('nombre') border-red-500 @enderror">
            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Juego o Deporte *</label>
            <input type="text" name="juego_deporte" value="{{ old('juego_deporte') }}" class="w-full mt-1 border rounded p-2 @error('juego_deporte') border-red-500 @enderror">
            @error('juego_deporte') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium text-gray-700">Fecha y Hora *</label>
                <input type="datetime-local" name="fecha_evento" value="{{ old('fecha_evento') }}" class="w-full mt-1 border rounded p-2 @error('fecha_evento') border-red-500 @enderror">
                @error('fecha_evento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block font-medium text-gray-700">Cupo (2 - 100)</label>
                <input type="number" name="cupo" value="{{ old('cupo', 16) }}" min="2" max="100" class="w-full mt-1 border rounded p-2 @error('cupo') border-red-500 @enderror">
                @error('cupo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Descripción (Opcional)</label>
            <textarea name="descripcion" rows="3" class="w-full mt-1 border rounded p-2">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700">Estado Inicial</label>
            <select name="activo" class="w-full mt-1 border rounded p-2">
                <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Abierto</option>
                <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">
            Guardar Torneo
        </button>
    </form>
</div>
@endsection