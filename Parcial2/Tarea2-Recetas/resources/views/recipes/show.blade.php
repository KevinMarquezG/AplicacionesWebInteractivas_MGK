<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $recipe->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('recipes.edit', $recipe) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md text-sm font-medium hover:bg-indigo-100">Editar</a>
                <a href="{{ route('recipes.index') }}" class="px-3 py-1.5 border text-gray-600 rounded-md text-sm hover:bg-gray-50">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-6">
            <div class="flex gap-4 border-b pb-4 text-sm text-gray-600">
                <div><span class="font-semibold">Categoría:</span> {{ ucfirst($recipe->category) }}</div>
                <div><span class="font-semibold">Tiempo:</span> {{ $recipe->cooking_time }} minutos</div>
                <div><span class="font-semibold">Dificultad:</span> {{ ucfirst($recipe->difficulty) }}</div>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3">Ingredientes</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    @foreach(array_filter(explode("\n", str_replace("\r", "", $recipe->ingredients))) as $ingredient)
                        <li>{{ trim($ingredient) }}</li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3">Instrucciones paso a paso</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    @foreach(array_filter(explode("\n", str_replace("\r", "", $recipe->steps))) as $step)
                        <li>{{ trim($step) }}</li>
                    @endforeach
                </ol>
            </div>

            @if($recipe->notes)
                <div class="bg-amber-50 p-4 rounded-lg border-l-4 border-amber-400">
                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wide mb-1">Notas personales</h4>
                    <p class="text-sm text-amber-900 whitespace-pre-line">{{ $recipe->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>