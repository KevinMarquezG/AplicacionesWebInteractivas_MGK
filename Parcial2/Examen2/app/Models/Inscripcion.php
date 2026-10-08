<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'torneo_id',
        'user_id',
        'fecha_inscripcion',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
