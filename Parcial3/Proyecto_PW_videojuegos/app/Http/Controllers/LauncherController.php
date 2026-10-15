<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LauncherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $viewTab = $request->query('tab', 'catalog');

        $categories = Category::all();
        $selectedCategory = $request->query('category');
        $search = $request->query('search');

        $catalogQuery = Game::where('status', 'published')
            ->with(['categories', 'developer'])
            ->latest();

        if ($selectedCategory) {
            $catalogQuery->whereHas('categories', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($search) {
            $catalogQuery->where('title', 'like', "%{$search}%");
        }

        $catalogGames = $catalogQuery->get();

        $libraryGames = $user->library()
            ->with('categories')
            ->orderByPivot('acquired_at', 'desc')
            ->get();

        $ownedGameIds = $user->library()->pluck('games.id')->toArray();

        return view('launcher.index', compact(
            'viewTab',
            'catalogGames',
            'libraryGames',
            'categories',
            'selectedCategory',
            'search',
            'ownedGameIds'
        ));
    }

    public function addToLibrary(Request $request, Game $game)
    {
        $user = Auth::user();

        if ($user->library()->where('game_id', $game->id)->exists()) {
            return back()->with('info', 'Este juego ya está en tu biblioteca.');
        }

        if ($game->price > 0) {
            if ($user->wallet_balance < $game->price) {
                return back()->with('error', 'Saldo insuficiente en tu billetera.');
            }

            $user->decrement('wallet_balance', $game->price);
        }

        $user->library()->attach($game->id, [
            'acquired_at' => now(),
            'playtime_minutes' => 0,
            'is_installed' => false,
        ]);

        return redirect()->route('launcher.dashboard', ['tab' => 'library'])
            ->with('success', "¡{$game->title} ha sido añadido a tu biblioteca!");
    }
}

