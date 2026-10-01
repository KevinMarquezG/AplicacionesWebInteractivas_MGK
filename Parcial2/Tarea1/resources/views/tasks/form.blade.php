@csrf
<div class="space-y-4">
    <div>
        <label for="titulo" class="block text-sm font-medium text-slate-700 mb-1">Título *</label>
        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tarea->titulo ?? '') }}" required
            class="w-full border @error('titulo') border-rose-500 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        @error('titulo')
            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="descripcion" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="4"
            class="w-full border @error('descripcion') border-rose-500 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('descripcion', $tarea->descripcion ?? '') }}</textarea>
        @error('descripcion')
            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="estado" class="block text-sm font-medium text-slate-700 mb-1">Estado *</label>
            <select name="estado" id="estado" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                @foreach ($estados as $clave => $nombre)
                    <option value="{{ $clave }}" {{ old('estado', $tarea->estado ?? 'por_hacer') === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="prioridad" class="block text-sm font-medium text-slate-700 mb-1">Prioridad *</label>
            <select name="prioridad" id="prioridad" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                @foreach ($prioridades as $clave => $nombre)
                    <option value="{{ $clave }}" {{ old('prioridad', $tarea->prioridad ?? 'media') === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="vencimiento" class="block text-sm font-medium text-slate-700 mb-1">Fecha de Vencimiento</label>
            <input type="date" name="vencimiento" id="vencimiento" 
                value="{{ old('vencimiento', isset($tarea->vencimiento) ? $tarea->vencimiento->format('Y-m-d') : '') }}"
                class="w-full border @error('vencimiento') border-rose-500 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            @error('vencimiento')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>