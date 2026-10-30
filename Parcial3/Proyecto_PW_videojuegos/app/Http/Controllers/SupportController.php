<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function tickets(Request $request)
    {
        $filterStatus = $request->query('status', 'all');
        $filterPriority = $request->query('priority', 'all');

        // Casos de soporte del launcher
        $tickets = collect([
            (object)[
                'id' => 'TCK-1042',
                'user_name' => 'Alex Gamer',
                'user_email' => 'cliente@launcher.test',
                'game_title' => 'Cyber Odyssey 2088',
                'category' => 'Error de Descarga',
                'subject' => 'Fallo al verificar archivos locales en la actualización 1.0.1',
                'message' => 'Al hacer clic en descargar, la barra se detiene en 85% y marca error de checksum en el ejecutable.',
                'priority' => 'alta',
                'status' => 'abierto',
                'created_at' => now()->subHours(2),
            ],
            (object)[
                'id' => 'TCK-1039',
                'user_name' => 'Lucas99',
                'user_email' => 'lucas99@gmail.com',
                'game_title' => 'Stellar Vanguard',
                'category' => 'Billetera',
                'subject' => 'Saldo no reflejado tras recarga con tarjeta de débito',
                'message' => 'Hice una recarga de $25 USD pero mi saldo sigue mostrando $0.00 en la esquina superior del launcher.',
                'priority' => 'urgente',
                'status' => 'en_proceso',
                'created_at' => now()->subHours(5),
            ],
            (object)[
                'id' => 'TCK-1035',
                'user_name' => 'Indie Studios',
                'user_email' => 'dev@launcher.test',
                'game_title' => 'Cyber Odyssey 2088',
                'category' => 'Portal Desarrollador',
                'subject' => 'Consulta sobre el formato de banner en resolución 4K',
                'message' => 'Quisiéramos actualizar el banner hero a resolución panorámica 3840x2160 sin pérdida de calidad.',
                'priority' => 'media',
                'status' => 'en_proceso',
                'created_at' => now()->subDay(),
            ],
            (object)[
                'id' => 'TCK-1028',
                'user_name' => 'MartinaV',
                'user_email' => 'martina@gmail.com',
                'game_title' => 'Cyber Odyssey 2088',
                'category' => 'Reembolso',
                'subject' => 'Reembolso por incompatibilidad de tarjeta gráfica',
                'message' => 'Mi equipo no cumple los requisitos mínimos y el juego no inicia. Tengo menos de 30 minutos jugados.',
                'priority' => 'baja',
                'status' => 'resuelto',
                'created_at' => now()->subDays(2),
            ],
        ]);

        if ($filterStatus !== 'all') {
            $tickets = $tickets->where('status', $filterStatus);
        }

        if ($filterPriority !== 'all') {
            $tickets = $tickets->where('priority', $filterPriority);
        }

        $openCount = 12;
        $inProgressCount = 5;
        $resolvedToday = 18;
        $avgResponseTime = '24 min';

        return view('support.tickets.index', compact(
            'tickets',
            'filterStatus',
            'filterPriority',
            'openCount',
            'inProgressCount',
            'resolvedToday',
            'avgResponseTime'
        ));
    }

    public function resolve(Request $request, string $id)
    {
        return back()->with('success', "Ticket {$id} marcado como resuelto.");
    }
}