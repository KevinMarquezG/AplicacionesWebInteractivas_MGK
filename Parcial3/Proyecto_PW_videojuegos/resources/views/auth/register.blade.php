<!DOCTYPE html>
<html lang="es" class="h-full bg-[#080b11] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Nova Launcher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full flex items-center justify-center p-6 bg-cover bg-center" style="background-image: radial-gradient(circle at center, rgba(17, 24, 39, 0.9) 0%, rgba(8, 11, 17, 0.98) 100%), url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1600&q=80');">
    <div class="w-full max-w-lg bg-[#111726]/90 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-white" style="font-family: 'Rajdhani', sans-serif;">REGISTRO DE USUARIO</h1>
            <p class="text-sm text-slate-400 mt-1">Elige tu rol y únete a la plataforma</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Nombre o Gamertag</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Tipo de Perfil</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role_slug" value="client" class="peer sr-only" checked>
                        <div class="p-3 rounded-xl border border-slate-700 bg-[#090d15] peer-checked:border-cyan-400 peer-checked:bg-cyan-950/20 text-center transition">
                            <span class="block text-sm font-bold text-slate-200">Jugador / Cliente</span>
                            <span class="text-[11px] text-slate-400">Comprar y jugar</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role_slug" value="developer" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-slate-700 bg-[#090d15] peer-checked:border-cyan-400 peer-checked:bg-cyan-950/20 text-center transition">
                            <span class="block text-sm font-bold text-slate-200">Desarrollador</span>
                            <span class="text-[11px] text-slate-400">Subir videojuegos</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Confirmar</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                </div>
            </div>
            @error('password') <p class="text-rose-400 text-xs">{{ $message }}</p> @enderror

            <button type="submit" 
                    class="w-full mt-4 py-3 px-4 rounded-xl font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 text-white shadow-lg shadow-cyan-500/25 transition">
                Registrarse Ahora
            </button>
        </form>

        <div class="mt-5 text-center text-xs text-slate-400">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}" class="text-cyan-400 font-semibold hover:underline">Inicia sesión</a>
        </div>
    </div>
</body>
</html>
