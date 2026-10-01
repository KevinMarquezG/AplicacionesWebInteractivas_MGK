@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
    <h1 class="text-lg font-bold text-slate-800 mb-4">Editar Tarea</h1>

    <form method="POST" action="{{ route('tasks.update', $tarea) }}">
        @method('PUT')
        @include('tasks.form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm hover:bg-slate-50 transition">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Actualizar Tarea</button>
        </div>
    </form>
</div>
@endsection