<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Recetas
            </h2>
            <a href="{{ route('recipes.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg text-sm hover:bg-indigo-700 transition">
                + Nueva Receta
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtros y búsqueda combinada -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('recipes.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar por título</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej. Lasaña casera..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Categoría</label>
                    <select name="category" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todas las categorías</option>
                        @foreach(['desayuno', 'almuerzo', 'cena', 'postre', 'bebida'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="w-full py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'category']))
                        <a href="{{ route('recipes.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Resultados -->
        @if ($recipes->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-500 text-base">No se encontraron recetas con los criterios especificados.</p>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('recipes.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">Ver todas mis recetas</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recipes as $recipe)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden hover:shadow-md transition">
                        <div class="p-5">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span class="uppercase tracking-wider font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $recipe->category }}
                                </span>
                                <span>⏱ {{ $recipe->cooking_time }} min</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">
                                <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-indigo-600 transition">
                                    {{ $recipe->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500">Dificultad: <span class="capitalize font-medium">{{ $recipe->difficulty }}</span></p>
                        </div>
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 text-sm">
                            <a href="{{ route('recipes.show', $recipe) }}" class="text-gray-600 hover:text-gray-900 font-medium">Ver</a>
                            <a href="{{ route('recipes.edit', $recipe) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Editar</a>
                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta permanentemente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>