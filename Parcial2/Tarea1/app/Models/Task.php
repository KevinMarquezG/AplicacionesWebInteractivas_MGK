<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'vencimiento'
    ];

    protected $casts = [
        'vencimiento' => 'data'
    ];

    public const ESTADOS = [
        'por_hacer' => 'Por hacer',
        'en_curso' => 'En curso',
        'marcha' => 'Marcha'
    ];

    public const PRIORIDADES = [
        'baja' => 'Baja',
        'media' => 'Media',
        'alta' => 'Alta' 
    ];

    public function estaVencida(): bool{
        return $this->vencimiento
            && $this->vencimiento->isPast()
            && $this->estado == 'hecho';
    }
}
