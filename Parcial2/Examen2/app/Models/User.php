<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function esAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'inscripciones');
    }
}