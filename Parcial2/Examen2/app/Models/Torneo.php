<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Torneo extends Model
{
    protected $fillable = [
        'nombre',
        'juego_deporte',
        'fecha_evento',
        'cupo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
        'activo' => 'boolean',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function participantes()
    {
        return $this->belongsToMany(User::class, 'inscripciones');
    }

    // Regla de disponibilidad para listar públicamente
    public function estaDisponible(): bool
    {
        $fechaFutura = $this->fecha_evento->isFuture();
        $cupoLibre = $this->inscripciones()->count() < $this->cupo;
        return $this->activo && $fechaFutura && $cupoLibre;
    }

    public function getPlazasDisponiblesAttribute(): int
    {
        return max(0, $this->cupo - $this->inscripciones()->count());
    }
}