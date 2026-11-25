<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeveloperGameController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener solo los juegos pertenecientes a la empresa o desarrollador autenticado
        $games = Game::where('developer_id', $user->id)
            ->with(['categories', 'owners'])
            ->withCount('owners')
            ->latest()
            ->get();

        $totalGames = $games->count();
        $publishedGames = $games->where('status', 'published')->count();
        $totalPlayers = $games->sum('owners_count');
        $estimatedRevenue = $games->sum(function ($game) {
            return $game->price * $game->owners_count;
        });

        return view('developer.games.index', compact(
            'games',
            'totalGames',
            'publishedGames',
            'totalPlayers',
            'estimatedRevenue'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('developer.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'version' => ['required', 'string', 'max:20'],
            'executable_name' => ['nullable', 'string', 'max:100'],
            'download_url' => ['nullable', 'url', 'max:500'],
            'release_date' => ['nullable', 'date'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cover_url' => ['nullable', 'url'],
            'banner_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:8192'],
            'banner_url' => ['nullable', 'url'],
        ]);

        // Manejo de Portada (Archivo local o URL directa)
        $coverPath = null;
        if ($request->hasFile('cover_file')) {
            $coverPath = Storage::url($request->file('cover_file')->store('covers', 'public'));
        } elseif ($request->filled('cover_url')) {
            $coverPath = $request->cover_url;
        }

        // Manejo de Banner
        $bannerPath = null;
        if ($request->hasFile('banner_file')) {
            $bannerPath = Storage::url($request->file('banner_file')->store('banners', 'public'));
        } elseif ($request->filled('banner_url')) {
            $bannerPath = $request->banner_url;
        }

        $slug = Str::slug($validated['title']);
        if (Game::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        $game = Game::create([
            'developer_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'cover_image' => $coverPath ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80',
            'banner_image' => $bannerPath ?? 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1200&q=80',
            'executable_name' => $validated['executable_name'] ?? (Str::slug($validated['title']) . '.exe'),
            'download_url' => $validated['download_url'],
            'version' => $validated['version'],
            // Toda nueva publicación debe pasar por moderación.
            'status' => 'pending_approval',
            'release_date' => $validated['release_date'] ?? now(),
        ]);

        $game->categories()->sync($validated['categories']);

        return redirect()->route('developer.games.index')
            ->with('success', "¡El videojuego '{$game->title}' fue enviado a revisión. Estará disponible cuando soporte técnico o administración lo aprueben.");
    }

    public function edit(Game $game)
    {
        if ($game->developer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes autorización para editar este título.');
        }

        $categories = Category::all();
        $selectedCategories = $game->categories->pluck('id')->toArray();

        return view('developer.games.edit', compact('game', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Game $game)
    {
        if ($game->developer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes autorización para modificar este título.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'version' => ['required', 'string', 'max:20'],
            'executable_name' => ['nullable', 'string', 'max:100'],
            'download_url' => ['nullable', 'url', 'max:500'],
            'release_date' => ['nullable', 'date'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cover_url' => ['nullable', 'url'],
            'banner_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:8192'],
            'banner_url' => ['nullable', 'url'],
        ]);

        if ($request->hasFile('cover_file')) {
            $game->cover_image = Storage::url($request->file('cover_file')->store('covers', 'public'));
        } elseif ($request->filled('cover_url')) {
            $game->cover_image = $request->cover_url;
        }

        if ($request->hasFile('banner_file')) {
            $game->banner_image = Storage::url($request->file('banner_file')->store('banners', 'public'));
        } elseif ($request->filled('banner_url')) {
            $game->banner_image = $request->banner_url;
        }

        $game->title = $validated['title'];
        $game->short_description = $validated['short_description'];
        $game->description = $validated['description'];
        $game->price = $validated['price'];
        $game->version = $validated['version'];
        $game->executable_name = $validated['executable_name'] ?? $game->executable_name;
        $game->download_url = $validated['download_url'];
        // Los cambios del desarrollador vuelven a requerir aprobación.
        $game->status = 'pending_approval';
        $game->release_date = $validated['release_date'];
        $game->save();

        $game->categories()->sync($validated['categories']);

        return redirect()->route('developer.games.index')
            ->with('success', "¡'{$game->title}' fue actualizado y enviado nuevamente a revisión!");
    }

    public function destroy(Game $game)
    {
        if ($game->developer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes autorización para eliminar este título.');
        }

        $title = $game->title;
        $game->delete();

        return redirect()->route('developer.games.index')
            ->with('success', "El título '{$title}' ha sido eliminado del catálogo.");
    }
}