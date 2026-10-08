<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    // Inscribirse en un torneo (Jugador)
    public function store(Request $request, Torneo $torneo)
    {
        $user = auth()->user();

        // 1. Validar torneo abierto por el admin
        if (!$torneo->activo) {
            return back()->with('error', 'El torneo está cerrado por el organizador.');
        }

        // 2. Validar fecha futura
        if (Carbon::parse($torneo->fecha_evento)->isPast()) {
            return back()->with('error', 'No puedes inscribirte a un torneo cuya fecha ya ha pasado.');
        }

        // 3. Validar cupo disponible
        if ($torneo->inscripciones()->count() >= $torneo->cupo) {
            return back()->with('error', 'El cupo de este torneo está lleno.');
        }

        // 4. Validar duplicados
        $yaInscrito = Inscripcion::where('torneo_id', $torneo->id)->where('user_id', $user->id)->exists();
        if ($yaInscrito) {
            return back()->with('error', 'Ya te encuentras inscrito en este torneo.');
        }

        Inscripcion::create([
            'torneo_id' => $torneo->id,
            'user_id' => $user->id,
            'fecha_inscripcion' => now(),
        ]);

        return back()->with('success', '¡Inscripción realizada con éxito!');
    }

    // Listado de "Mis Torneos" (Jugador)
    public function misTorneos()
    {
        $inscripciones = auth()->user()->inscripciones()->with('torneo')->latest()->get();
        return view('jugador.mis-torneos', compact('inscripciones'));
    }

    // Cancelar inscripción (Jugador)
    public function cancelar(Inscripcion $inscripcion)
    {
        if ($inscripcion->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para cancelar esta inscripción.');
        }

        if (Carbon::parse($inscripcion->torneo->fecha_evento)->isPast()) {
            return back()->with('error', 'No puedes cancelar tu inscripción porque el evento ya inició o concluyó.');
        }

        $inscripcion->delete(); // Libera la plaza
        return back()->with('success', 'Inscripción cancelada. La plaza ha sido liberada.');
    }

    // Listar participantes de un torneo (Admin)
    public function verParticipantes(Torneo $torneo)
    {
        $inscripciones = $torneo->inscripciones()->with('user')->get();
        return view('admin.torneos.participantes', compact('torneo', 'inscripciones'));
    }

    // Dar de baja participante (Admin)
    public function bajaAdmin(Inscripcion $inscripcion)
    {
        $inscripcion->delete();
        return back()->with('success', 'El participante fue dado de baja correctamente.');
    }
}
