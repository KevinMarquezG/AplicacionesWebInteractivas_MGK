<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar: {{ $recipe->title }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('recipes.update', $recipe) }}" method="POST">
                @method('PUT')
                @include('recipes._form', ['recipe' => $recipe])
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('recipes.index') }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50 text-sm">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">Actualizar Receta</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>