<!DOCTYPE html>
<html lang="es" class="h-full bg-[#0b0e14] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Nova Launcher') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-gaming { font-family: 'Rajdhani', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col overflow-hidden select-none bg-[#0b0e14]">
    <!-- Topbar estilo Launcher de Escritorio -->
    <header class="h-16 bg-[#111622] border-b border-slate-800 flex items-center justify-between px-6 z-20">
        <div class="flex items-center space-x-8">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center font-gaming text-2xl font-bold text-white shadow-lg shadow-cyan-500/20">
                    N
                </div>
                <span class="font-gaming text-2xl font-bold tracking-wider text-white">NOVA<span class="text-cyan-400">PLAY</span></span>
            </div>

            <nav class="flex space-x-2">
                <a href="{{ route('launcher.dashboard', ['tab' => 'catalog']) }}" 
                   class="px-4 py-2 rounded-md font-semibold text-sm transition-all duration-200 {{ request('tab', 'catalog') === 'catalog' ? 'bg-slate-800 text-cyan-400 border-b-2 border-cyan-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                    TIENDA
                </a>
                <a href="{{ route('launcher.dashboard', ['tab' => 'library']) }}" 
                   class="px-4 py-2 rounded-md font-semibold text-sm transition-all duration-200 {{ request('tab', 'catalog') === 'library' ? 'bg-slate-800 text-cyan-400 border-b-2 border-cyan-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                    BIBLIOTECA
                </a>
            </nav>
        </div>

        <div class="flex items-center space-x-5">
            <div class="bg-[#182030] px-3.5 py-1.5 rounded-full border border-slate-700/60 flex items-center space-x-2 text-xs">
                <span class="text-slate-400">Billetera:</span>
                <span class="text-emerald-400 font-bold">${{ number_format(Auth::user()->wallet_balance, 2) }}</span>
            </div>

            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm font-semibold leading-tight text-slate-200">{{ Auth::user()->name }}</p>
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-indigo-950 text-indigo-400 border border-indigo-800/50">
                        {{ Auth::user()->role->name }}
                    </span>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-cyan-500/50 flex items-center justify-center font-bold text-cyan-400">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" title="Cerrar sesión" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8 bg-radial from-[#121929] to-[#0b0e14]">
        @if (session('status') || session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-600/50 text-emerald-300 text-sm">
                {{ session('status') ?? session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
