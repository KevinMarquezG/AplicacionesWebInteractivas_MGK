@extends('layouts.launcher')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <a href="{{ route('developer.games.index') }}" class="inline-flex items-center text-xs text-cyan-400 hover:underline mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight" style="font-family: 'Rajdhani', sans-serif;">
                PUBLICAR NUEVO VIDEOJUEGO
            </h1>
        </div>
    </div>

    <form method="POST" action="{{ route('developer.games.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Columna Izquierda: Información de Ficha -->
            <div class="lg:col-span-7 bg-[#111726] border border-slate-800 rounded-2xl p-6 space-y-5 shadow-xl">
                <h3 class="text-sm font-bold uppercase tracking-wider text-cyan-400 border-b border-slate-800 pb-2">
                    1. Información del Título
                </h3>

                <div>
                    <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Título del Videojuego *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                           placeholder="Ej: Starfall Warriors"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                    @error('title') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Precio (USD) *</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400 font-bold">$</span>
                            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', '0.00') }}" required
                                   class="w-full pl-8 pr-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm font-semibold focus:outline-none focus:border-cyan-500">
                        </div>
                        <span class="text-[10px] text-slate-500 mt-1 block">Ingresa 0.00 si es Free-to-Play.</span>
                    </div>

                    <div>
                        <label for="version" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Versión Inicial *</label>
                        <input type="text" name="version" id="version" value="{{ old('version', '1.0.0') }}" required
                               placeholder="1.0.0"
                               class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <!-- Géneros -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Géneros y Categorías *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-48 overflow-y-auto p-3 rounded-xl bg-[#090d15] border border-slate-700/80">
                        @foreach ($categories as $cat)
                            <label class="flex items-center space-x-2 text-xs text-slate-300 hover:text-white cursor-pointer select-none">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                       {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                                       class="rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-0">
                                <span>{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="short_description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Descripción Breve (Para Tienda) *</label>
                    <textarea name="short_description" id="short_description" rows="2" required
                              placeholder="Sinopsis atractiva para las tarjetas de la tienda..."
                              class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500 leading-relaxed">{{ old('short_description') }}</textarea>
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Descripción Completa y Requisitos *</label>
                    <textarea name="description" id="description" rows="6" required
                              placeholder="Detalles del gameplay, historia, requisitos mínimos de PC..."
                              class="w-full px-4 py-2.5 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-sm focus:outline-none focus:border-cyan-500 leading-relaxed">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Columna Derecha: Ejecutable y Arte Gráfico -->
            <div class="lg:col-span-5 space-y-6">

                <div class="bg-[#111726] border border-slate-800 rounded-2xl p-6 space-y-4 shadow-xl">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-cyan-400 border-b border-slate-800 pb-2">
                        2. Lanzador y Archivo
                    </h3>

                    <div>
                        <label for="executable_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Nombre del Ejecutable (.exe)</label>
                        <input type="text" name="executable_name" id="executable_name" value="{{ old('executable_name') }}"
                               placeholder="Ej: GameLauncher.exe"
                               class="w-full px-4 py-2 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-xs focus:outline-none focus:border-cyan-500">
                        <span class="text-[10px] text-slate-500 mt-1 block">El cliente lo ejecutará al presionar 'JUGAR'.</span>
                    </div>

                    <div>
                        <label for="download_url" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">URL de Descarga o Build</label>
                        <input type="url" name="download_url" id="download_url" value="{{ old('download_url') }}"
                               placeholder="https://cdn.tu-estudio.com/builds/v1.0.0.zip"
                               class="w-full px-4 py-2 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-xs focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <label for="release_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Fecha</label>
                            <input type="date" name="release_date" id="release_date" value="{{ old('release_date', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-xs focus:outline-none focus:border-cyan-500">
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Estado *</label>
                            <select name="status" id="status" class="w-full px-3 py-2 rounded-xl bg-[#090d15] border border-slate-700/80 text-slate-100 text-xs focus:outline-none focus:border-cyan-500">
                                <option value="published">Publicado</option>
                                <option value="pending_approval">En Revisión</option>
                                <option value="draft">Borrador</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-[#111726] border border-slate-800 rounded-2xl p-6 space-y-4 shadow-xl">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-cyan-400 border-b border-slate-800 pb-2">
                        3. Portadas y Banners
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Portada Vertical (Cover 3:4)</label>
                        <input type="file" name="cover_file" accept="image/*"
                               class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-cyan-400 hover:file:bg-slate-700 cursor-pointer">
                        <input type="url" name="cover_url" value="{{ old('cover_url') }}" placeholder="O ingresa enlace web de la imagen..."
                               class="w-full mt-2 px-3 py-1.5 rounded-lg bg-[#090d15] border border-slate-700/60 text-slate-200 text-xs placeholder-slate-600 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Banner Horizontal (Hero 16:9)</label>
                        <input type="file" name="banner_file" accept="image/*"
                               class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-cyan-400 hover:file:bg-slate-700 cursor-pointer">
                        <input type="url" name="banner_url" value="{{ old('banner_url') }}" placeholder="O ingresa enlace web del banner..."
                               class="w-full mt-2 px-3 py-1.5 rounded-lg bg-[#090d15] border border-slate-700/60 text-slate-200 text-xs placeholder-slate-600 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 text-white shadow-lg shadow-cyan-500/25 transition transform active:scale-98">
                    Confirmar y Publicar
                </button>

            </div>

        </div>
    </form>

</div>
@endsection