<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador Demo
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@torneos.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);

        // Jugador Demo
        User::create([
            'name' => 'Jugador Demo',
            'email' => 'jugador@torneos.com',
            'password' => Hash::make('jugador12345'),
            'role' => 'jugador',
        ]);

        // Torneo Demo
        Torneo::create([
            'nombre' => 'Copa de Campeones FC',
            'juego_deporte' => 'Fútbol 7',
            'fecha_evento' => Carbon::now()->addDays(10)->setTime(18, 0),
            'cupo' => 8,
            'descripcion' => 'Torneo eliminatorio directo en campo sintético.',
            'activo' => true,
        ]);
    }
}
