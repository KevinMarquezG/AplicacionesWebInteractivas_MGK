@csrf
<div class="space-y-5">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título de la receta *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $recipe->title ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
            <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione...</option>
                @foreach(['desayuno', 'almuerzo', 'cena', 'postre', 'bebida'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $recipe->category ?? '') === $cat ? 'selected' : '' }}>
                        {{ ucfirst($cat) }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-1">Tiempo (minutos) *</label>
            <input type="number" min="1" name="cooking_time" id="cooking_time" value="{{ old('cooking_time', $recipe->cooking_time ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('cooking_time')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Dificultad *</label>
            <select name="difficulty" id="difficulty" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione...</option>
                @foreach(['baja', 'media', 'alta'] as $diff)
                    <option value="{{ $diff }}" {{ old('difficulty', $recipe->difficulty ?? '') === $diff ? 'selected' : '' }}>
                        {{ ucfirst($diff) }}
                    </option>
                @endforeach
            </select>
            @error('difficulty')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredientes (uno por línea) *</label>
        <textarea name="ingredients" id="ingredients" rows="4" placeholder="2 tazas de harina&#10;1 pizca de sal&#10;3 huevos" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('ingredients', $recipe->ingredients ?? '') }}</textarea>
        @error('ingredients')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="steps" class="block text-sm font-medium text-gray-700 mb-1">Pasos de preparación (uno por línea) *</label>
        <textarea name="steps" id="steps" rows="5" placeholder="Mezclar los ingredientes secos&#10;Batir los huevos e incorporar&#10;Hornear a 180°C por 30 minutos" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('steps', $recipe->steps ?? '') }}</textarea>
        @error('steps')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas personales (opcional)</label>
        <textarea name="notes" id="notes" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $recipe->notes ?? '') }}</textarea>
        @error('notes')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>