<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Si ya está autenticado, va directo a su panel
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirección directa y forzada según el rol
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        $availableRoles = Role::whereIn('slug', ['client', 'developer'])->get();
        return view('auth.register', compact('availableRoles'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_slug' => ['required', 'in:client,developer'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $role = Role::where('slug', $validated['role_slug'])->firstOrFail();

        $user = User::create([
            'role_id' => $role->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'wallet_balance' => 0.00,
        ]);

        Auth::login($user);

        return $this->redirectByRole($user)->with('status', '¡Bienvenido a la plataforma!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirige a cada rol a su vista correspondiente
     */
    protected function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isDeveloper()) {
            return redirect()->route('developer.games.index');
        }

        if ($user->isSupport()) {
            return redirect()->route('support.tickets');
        }

        // Cliente (Tienda y Biblioteca)
        return redirect()->route('launcher.dashboard');
    }
}
