@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow border">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Crear Cuenta de Jugador</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Nombre completo *</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Correo Electrónico *</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Contraseña *</label>
            <input type="password" name="password" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700">Confirmar Contraseña *</label>
            <input type="password" name="password_confirmation" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200">
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            Registrarme
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-semibold">Inicia sesión</a>
        </p>
    </form>
</div>
@endsection