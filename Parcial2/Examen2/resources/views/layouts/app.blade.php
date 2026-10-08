<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gestor de Torneos') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-indigo-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('torneos.index') }}" class="font-bold text-xl">TorneosHub</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('torneos.index') }}" class="hover:underline">Inicio</a>
                @auth
                    @if(auth()->user()->esAdmin())
                        <a href="{{ route('admin.torneos.index') }}" class="font-semibold text-yellow-300 hover:underline">Panel Admin</a>
                    @else
                        <a href="{{ route('jugador.mis-torneos') }}" class="hover:underline">Mis Torneos</a>
                    @endif
                    <span class="text-sm bg-indigo-700 px-2 py-1 rounded">({{ auth()->user()->role }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-sm px-3 py-1 rounded">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Ingresar</a>
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 font-semibold px-3 py-1 rounded hover:bg-gray-100">Registro</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>