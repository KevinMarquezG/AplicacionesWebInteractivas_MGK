<?php

// app/Http/Controllers/TorneoController.php
namespace App\Http\Controllers;

use App\Models\Torneo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    // Listado Público: Solo disponibles (abiertos, fecha futura, con cupo) ordenados por fecha próxima
    public function index()
    {
        $torneos = Torneo::withCount('inscripciones')
            ->where('activo', true)
            ->where('fecha_evento', '>', Carbon::now())
            ->havingRaw('cupo > inscripciones_count')
            ->orderBy('fecha_evento', 'asc')
            ->get();

        return view('torneos.index', compact('torneos'));
    }

    // Detalle accesible directamente (incluso si está lleno o cerrado)
    public function show($id)
    {
        $torneo = Torneo::with(['participantes'])->withCount('inscripciones')->findOrFail($id);
        $estaInscrito = auth()->check() ? $torneo->participantes->contains(auth()->id()) : false;

        return view('torneos.show', compact('torneo', 'estaInscrito'));
    }

    // Panel Admin
    public function adminIndex()
    {
        $torneos = Torneo::withCount('inscripciones')->orderBy('fecha_evento', 'desc')->get();
        return view('admin.torneos.index', compact('torneos'));
    }

    public function create()
    {
        return view('admin.torneos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'juego_deporte' => 'required|string|max:255',
            'fecha_evento' => 'required|date|after:now',
            'cupo' => 'nullable|integer|between:2,100',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ], [
            'nombre.required' => 'El nombre del torneo es obligatorio.',
            'juego_deporte.required' => 'El juego o deporte es obligatorio.',
            'fecha_evento.required' => 'La fecha del evento es obligatoria.',
            'fecha_evento.after' => 'La fecha del evento debe ser una fecha futura.',
            'cupo.between' => 'El cupo debe estar entre 2 y 100 participantes.',
        ]);

        Torneo::create([
            'nombre' => $request->nombre,
            'juego_deporte' => $request->juego_deporte,
            'fecha_evento' => $request->fecha_evento,
            'cupo' => $request->cupo ?? 16,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.torneos.index')->with('success', 'Torneo creado exitosamente.');
    }

    public function edit(Torneo $torneo)
    {
        return view('admin.torneos.edit', compact('torneo'));
    }

    public function update(Request $request, Torneo $torneo)
    {
        $inscritos = $torneo->inscripciones()->count();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'juego_deporte' => 'required|string|max:255',
            'fecha_evento' => 'required|date|after:now',
            'cupo' => "required|integer|between:2,100|min:{$inscritos}",
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ], [
            'nombre.required' => 'El nombre del torneo es obligatorio.',
            'juego_deporte.required' => 'El juego o deporte es obligatorio.',
            'fecha_evento.required' => 'La fecha del evento es obligatoria.',
            'fecha_evento.after' => 'La fecha del evento debe ser posterior a este momento.',
            'cupo.between' => 'El cupo debe estar entre 2 y 100 participantes.',
            'cupo.min' => "El cupo no puede ser menor a {$inscritos} (participantes ya inscritos).",
        ]);

        $torneo->update([
            'nombre' => $request->nombre,
            'juego_deporte' => $request->juego_deporte,
            'fecha_evento' => $request->fecha_evento,
            'cupo' => $request->cupo,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.torneos.index')->with('success', 'Torneo actualizado exitosamente.');
    }

    public function destroy(Torneo $torneo)
    {
        $torneo->delete(); // Elimina en cascada las inscripciones según la llave foránea
        return redirect()->route('admin.torneos.index')->with('success', 'Torneo eliminado junto con sus inscripciones.');
    }
}
