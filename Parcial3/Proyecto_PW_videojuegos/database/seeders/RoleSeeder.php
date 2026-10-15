<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrador', 'slug' => 'admin', 'description' => 'Control total del sistema'],
            ['name' => 'Cliente', 'slug' => 'client', 'description' => 'Jugador de la plataforma'],
            ['name' => 'Soporte Técnico', 'slug' => 'support', 'description' => 'Atención al usuario y tickets'],
            ['name' => 'Desarrollador', 'slug' => 'developer', 'description' => 'Creador y publicador de juegos'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['slug' => $roleData['slug']], $roleData);
        }

        $adminRole = Role::where('slug', 'admin')->first();
        $devRole = Role::where('slug', 'developer')->first();
        $clientRole = Role::where('slug', 'client')->first();
        $supportRole = Role::where('slug', 'support')->first();

        // Usuarios para pruebas
        $admin = User::firstOrCreate(
            ['email' => 'admin@launcher.test'],
            [
                'name' => 'Admin Master',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password123'),
                'wallet_balance' => 500.00,
            ]
        );

        $developer = User::firstOrCreate(
            ['email' => 'dev@launcher.test'],
            [
                'name' => 'Indie Studios',
                'role_id' => $devRole->id,
                'password' => Hash::make('password123'),
                'wallet_balance' => 0.00,
            ]
        );

        $client = User::firstOrCreate(
            ['email' => 'cliente@launcher.test'],
            [
                'name' => 'Alex Gamer',
                'role_id' => $clientRole->id,
                'password' => Hash::make('password123'),
                'wallet_balance' => 120.00,
            ]
        );

        User::firstOrCreate(
            ['email' => 'support@launcher.test'],
            [
                'name' => 'Sara Soporte',
                'role_id' => $supportRole->id,
                'password' => Hash::make('password123'),
            ]
        );

        // Categorías
        $catAccion = Category::firstOrCreate(['slug' => 'accion'], ['name' => 'Acción']);
        $catRpg = Category::firstOrCreate(['slug' => 'rpg'], ['name' => 'RPG / Aventura']);
        $catSciFi = Category::firstOrCreate(['slug' => 'sci-fi'], ['name' => 'Ciencia Ficción']);

        // Videojuegos de prueba
        $game1 = Game::firstOrCreate(
            ['slug' => 'cyber-odyssey-2088'],
            [
                'developer_id' => $developer->id,
                'title' => 'Cyber Odyssey 2088',
                'short_description' => 'Un RPG futurista inmersivo en una megalópolis cyberpunk iluminada por neón.',
                'description' => 'Explora distritos peligrosos, piratea redes corporativas y personaliza tu exoesqueleto de combate.',
                'price' => 39.99,
                'cover_image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'release_date' => now()->subMonths(2),
            ]
        );
        $game1->categories()->sync([$catAccion->id, $catRpg->id, $catSciFi->id]);

        $game2 = Game::firstOrCreate(
            ['slug' => 'stellar-vanguard'],
            [
                'developer_id' => $developer->id,
                'title' => 'Stellar Vanguard',
                'short_description' => 'Combate táctico de naves espaciales en el confín del universo conocido.',
                'description' => 'Comanda tu flota espacial, coloniza asteroides y defiende la estación central.',
                'price' => 0.00,
                'cover_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'release_date' => now()->subMonths(1),
            ]
        );
        $game2->categories()->sync([$catAccion->id, $catSciFi->id]);

        // Asignar juego a biblioteca del cliente
        if (!$client->library()->where('game_id', $game1->id)->exists()) {
            $client->library()->attach($game1->id, [
                'acquired_at' => now()->subDays(10),
                'playtime_minutes' => 450,
                'is_installed' => true,
            ]);
        }
    }
}
