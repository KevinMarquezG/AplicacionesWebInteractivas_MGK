@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow border">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Iniciar Sesión</h2>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200 @error('email') border-red-500 @enderror" autofocus>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Contraseña</label>
            <input type="password" name="password" 
                   class="w-full mt-1 border rounded p-2 focus:ring focus:ring-indigo-200 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600">
            <label for="remember" class="ml-2 text-sm text-gray-600">Recordar sesión</label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            Entrar
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
            ¿No tienes cuenta? 
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-semibold">Regístrate aquí</a>
        </p>
    </form>
</div>
@endsection