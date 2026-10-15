<!DOCTYPE html>
<html lang="es" class="h-full bg-[#080b11] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Nova Launcher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full flex items-center justify-center p-6 bg-cover bg-center" style="background-image: radial-gradient(circle at center, rgba(17, 24, 39, 0.9) 0%, rgba(8, 11, 17, 0.98) 100%), url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1600&q=80');">
    <div class="w-full max-w-md bg-[#111726]/90 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-indigo-600 items-center justify-center font-bold text-3xl text-white shadow-lg shadow-cyan-500/30 mb-4" style="font-family: 'Rajdhani', sans-serif;">
                N
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white" style="font-family: 'Rajdhani', sans-serif;">ACCESO AL LAUNCHER</h1>
            <p class="text-sm text-slate-400 mt-1">Conecta con tu catálogo y comunidad gamer</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                @error('password') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-cyan-500 mr-2">
                    Recordar cuenta
                </label>
                <a href="#" class="text-cyan-400 hover:underline">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 text-white shadow-lg shadow-cyan-500/25 transition">
                Entrar al Sistema
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-800 text-center text-xs text-slate-400">
            ¿Aún no tienes cuenta? 
            <a href="{{ route('register') }}" class="text-cyan-400 font-semibold hover:underline">Crear cuenta nueva</a>
        </div>
    </div>
</body>
</html>