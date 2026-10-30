<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalUsers = User::count();
        $totalGames = Game::count();
        $pendingGamesCount = Game::where('status', 'pending_approval')->count();
        $publishedGamesCount = Game::where('status', 'published')->count();

        // Cola de juegos pendientes de visto bueno
        $pendingGames = Game::where('status', 'pending_approval')
            ->with(['developer', 'categories'])
            ->latest()
            ->get();

        // Lista de usuarios con filtro por rol
        $roles = Role::all();
        $selectedRole = $request->query('role');
        
        $usersQuery = User::with('role')->latest();
        if ($selectedRole) {
            $usersQuery->whereHas('role', fn($q) => $q->where('slug', $selectedRole));
        }
        $users = $usersQuery->paginate(10);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGames',
            'pendingGamesCount',
            'publishedGamesCount',
            'pendingGames',
            'users',
            'roles',
            'selectedRole'
        ));
    }

    public function approveGame(Game $game)
    {
        $game->update(['status' => 'published']);
        return back()->with('success', "El videojuego '{$game->title}' ha sido aprobado y publicado en la tienda.");
    }

    public function rejectGame(Game $game)
    {
        $game->update(['status' => 'rejected']);
        return back()->with('info', "El videojuego '{$game->title}' ha sido rechazado.");
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes suspender tu propia cuenta.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $statusStr = $user->is_active ? 'activada' : 'suspendida';

        return back()->with('success', "La cuenta de {$user->name} ha sido {$statusStr}.");
    }

    public function changeUserRole(Request $request, User $user)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', "Rol actualizado para {$user->name}.");
    }
}