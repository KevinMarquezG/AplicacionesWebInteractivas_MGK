
@extends('layouts.launcher')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-white">
            Crear ticket de soporte
        </h1>
        <p class="text-sm text-slate-400 mt-2">
            Describe tu problema para que nuestro equipo pueda ayudarte.
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-rose-950 text-rose-300 p-4 rounded-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('client.tickets.store') }}"
          class="bg-[#111726] border border-slate-800 rounded-2xl p-6 space-y-5">

        @csrf

        <div>
            <label class="block text-sm text-slate-300 mb-2">
                Categoría
            </label>
            <select name="category" required
                    class="w-full bg-slate-900 text-white border border-slate-700 rounded-lg p-3">
                <option value="">Selecciona una categoría</option>
                <option value="Problemas técnicos">Problemas técnicos</option>
                <option value="Cuenta">Cuenta</option>
                <option value="Pagos">Pagos</option>
                <option value="Videojuegos">Videojuegos</option>
                <option value="Otros">Otros</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-2">
                Videojuego relacionado (opcional)
            </label>
            <select name="game_id"
                    class="w-full bg-slate-900 text-white border border-slate-700 rounded-lg p-3">
                <option value="">Sin videojuego relacionado</option>
                @foreach ($games as $game)
                    <option value="{{ $game->id }}"
                        {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-2">
                Asunto
            </label>
            <input type="text" name="subject"
                   value="{{ old('subject') }}"
                   maxlength="255" required
                   placeholder="Describe brevemente tu problema"
                   class="w-full bg-slate-900 text-white border border-slate-700 rounded-lg p-3">
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-2">
                Descripción del problema
            </label>
            <textarea name="message" rows="6" required maxlength="5000"
                      placeholder="Explica detalladamente qué sucede..."
                      class="w-full bg-slate-900 text-white border border-slate-700 rounded-lg p-3">{{ old('message') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-2">
                Prioridad
            </label>
            <select name="priority" required
                    class="w-full bg-slate-900 text-white border border-slate-700 rounded-lg p-3">
                <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
            </select>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('client.tickets.index') }}"
               class="px-5 py-3 rounded-lg bg-slate-800 text-white">
                Cancelar
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-lg bg-cyan-500 text-slate-950 font-bold">
                Enviar ticket
            </button>
        </div>

    </form>
</div>
@endsection
