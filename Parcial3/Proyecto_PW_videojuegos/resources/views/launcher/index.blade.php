@extends('layouts.launcher')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- PESTAÑA: CATÁLOGO / TIENDA -->
    @if ($viewTab === 'catalog')
        <!-- Banner Destacado -->
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-purple-900/60 to-slate-900 border border-slate-800 p-8 md:p-12 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl z-10 space-y-4">
                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 rounded-full">Lanzamiento Destacado</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight" style="font-family: 'Rajdhani', sans-serif;">CYBER ODYSSEY 2088</h2>
                <p class="text-slate-300 text-sm leading-relaxed">Adéntrate en la megalópolis más hostil y fascinante. Personaliza armas cibernéticas y desafía a las corporaciones.</p>
                <div class="flex items-center space-x-4 pt-2">
                    <span class="text-2xl font-bold text-white">$39.99 USD</span>
                    @if (in_array(1, $ownedGameIds))
                        <span class="px-4 py-2 bg-slate-800 text-slate-300 font-semibold rounded-xl text-sm border border-slate-700">En tu biblioteca</span>
                    @else
                        <button class="px-6 py-2.5 rounded-xl font-bold text-sm bg-cyan-500 hover:bg-cyan-400 text-slate-950 uppercase tracking-wider transition">
                            Comprar Ahora
                        </button>
                    @endif
                </div>
            </div>
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80" 
                     alt="Destacado" class="rounded-2xl shadow-2xl border border-slate-700/50 max-h-72 object-cover w-full">
            </div>
        </div>

        <!-- Filtros y Buscador -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pb-2">
            <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto">
                <a href="{{ route('launcher.dashboard', ['tab' => 'catalog']) }}" 
                   class="px-4 py-1.5 rounded-lg text-xs font-semibold {{ empty($selectedCategory) ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700' }}">
                    Todos
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('launcher.dashboard', ['tab' => 'catalog', 'category' => $cat->slug]) }}" 
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold {{ $selectedCategory === $cat->slug ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('launcher.dashboard') }}" class="w-full md:w-72">
                <input type="hidden" name="tab" value="catalog">
                @if ($selectedCategory) <input type="hidden" name="category" value="{{ $selectedCategory }}"> @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar en la tienda..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        <!-- Grilla de Juegos del Catálogo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($catalogGames as $game)
                <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden hover:border-slate-700 transition flex flex-col justify-between group">
                    <div>
                        <div class="h-44 w-full overflow-hidden relative">
                            <img src="{{ $game->cover_image ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80' }}" 
                                 alt="{{ $game->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 right-3 bg-slate-950/80 backdrop-blur-md px-2.5 py-1 rounded-md text-xs font-bold text-white border border-slate-700">
                                {{ $game->isFree() ? 'GRATIS' : '$' . number_format($game->price, 2) }}
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach ($game->categories as $category)
                                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-800 text-cyan-400">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                            <h3 class="text-base font-bold text-white group-hover:text-cyan-400 transition">{{ $game->title }}</h3>
                            <p class="text-xs text-slate-400 line-clamp-2 mt-1.5 leading-relaxed">{{ $game->short_description }}</p>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        @if (in_array($game->id, $ownedGameIds))
                            <a href="{{ route('launcher.dashboard', ['tab' => 'library']) }}" 
                               class="w-full block text-center py-2.5 px-4 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-cyan-400 border border-slate-700 transition">
                                En Biblioteca
                            </a>
                        @else
                            <form method="POST" action="{{ route('launcher.acquire', $game) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 text-white shadow-md shadow-cyan-500/20 transition">
                                    {{ $game->isFree() ? 'Obtener Gratis' : 'Comprar $' . number_format($game->price, 2) }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500">
                    <p class="text-base">No se encontraron títulos disponibles.</p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- PESTAÑA: BIBLIOTECA DEL USUARIO (Estilo Steam) -->
    @if ($viewTab === 'library')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-4 bg-[#111726] border border-slate-800 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 px-2">
                    <h3 class="font-gaming text-lg font-bold text-white tracking-wide" style="font-family: 'Rajdhani', sans-serif;">MIS JUEGOS ({{ $libraryGames->count() }})</h3>
                    <span class="text-xs text-slate-400">Instalados: {{ $libraryGames->where('pivot.is_installed', true)->count() }}</span>
                </div>

                <div class="space-y-1.5 max-h-[600px] overflow-y-auto pr-1">
                    @forelse ($libraryGames as $index => $ownedGame)
                        <div class="p-3 rounded-xl flex items-center justify-between {{ $index === 0 ? 'bg-cyan-950/30 border border-cyan-500/40 text-white' : 'bg-slate-900/60 hover:bg-slate-800/60 text-slate-300' }} cursor-pointer transition">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $ownedGame->cover_image }}" class="w-10 h-10 rounded-lg object-cover">
                                <div>
                                    <p class="text-xs font-bold leading-snug">{{ $ownedGame->title }}</p>
                                    <p class="text-[10px] text-slate-400">{{ round($ownedGame->pivot->playtime_minutes / 60, 1) }} hrs jugadas</p>
                                </div>
                            </div>
                            <div>
                                @if ($ownedGame->pivot->is_installed)
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block" title="Instalado"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-slate-600 inline-block" title="En la nube"></span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-500 text-xs">
                            Tu biblioteca está vacía. Explora la tienda y añade juegos.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Panel de Detalle del Juego Seleccionado -->
            <div class="lg:col-span-8 space-y-6">
                @if ($libraryGames->isNotEmpty())
                    @php $featuredOwned = $libraryGames->first(); @endphp
                    <div class="bg-[#111726] border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
                        <div class="h-64 relative bg-cover bg-center" style="background-image: linear-gradient(to bottom, rgba(17, 23, 38, 0.2) 0%, rgba(17, 23, 38, 0.95) 100%), url('{{ $featuredOwned->banner_image ?? $featuredOwned->cover_image }}');">
                            <div class="absolute bottom-6 left-6 right-6 flex items-end justify-between">
                                <div>
                                    <h2 class="text-3xl font-extrabold text-white" style="font-family: 'Rajdhani', sans-serif;">{{ $featuredOwned->title }}</h2>
                                    <p class="text-xs text-slate-300 mt-1">Versión: v{{ $featuredOwned->version }} | Adquirido: {{ \Carbon\Carbon::parse($featuredOwned->pivot->acquired_at)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    @if ($featuredOwned->pivot->is_installed)
                                        <button class="px-8 py-3 rounded-xl font-bold text-sm uppercase tracking-wider bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/25 flex items-center space-x-2 transition">
                                            <span>JUGAR</span>
                                        </button>
                                    @else
                                        <button class="px-8 py-3 rounded-xl font-bold text-sm uppercase tracking-wider bg-cyan-500 hover:bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/25 flex items-center space-x-2 transition">
                                            <span>INSTALAR</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-6 grid grid-cols-3 gap-4 border-b border-slate-800 text-center">
                            <div class="bg-slate-900/60 p-3 rounded-xl">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Tiempo Registrado</span>
                                <p class="text-lg font-bold text-cyan-400 mt-0.5">{{ round($featuredOwned->pivot->playtime_minutes / 60, 1) }} horas</p>
                            </div>
                            <div class="bg-slate-900/60 p-3 rounded-xl">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Estado Local</span>
                                <p class="text-lg font-bold {{ $featuredOwned->pivot->is_installed ? 'text-emerald-400' : 'text-slate-400' }} mt-0.5">
                                    {{ $featuredOwned->pivot->is_installed ? 'Listo para jugar' : 'No instalado' }}
                                </p>
                            </div>
                            <div class="bg-slate-900/60 p-3 rounded-xl">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Desarrollador</span>
                                <p class="text-lg font-bold text-slate-200 mt-0.5">{{ $featuredOwned->developer->name ?? 'Indie' }}</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-xs uppercase font-bold text-slate-400 mb-2">Acerca del juego</h4>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $featuredOwned->description ?? $featuredOwned->short_description }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-[#111726] border border-dashed border-slate-800 rounded-2xl p-16 text-center">
                        <h3 class="text-lg font-bold text-white mb-2">Tu biblioteca está vacía</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mb-6">Explora el catálogo de la tienda para reclamar juegos gratuitos o adquirir nuevos títulos.</p>
                        <a href="{{ route('launcher.dashboard', ['tab' => 'catalog']) }}" 
                           class="inline-block px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition">
                            Ir a la Tienda
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection