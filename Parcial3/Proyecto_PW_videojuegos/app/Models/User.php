<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'avatar',
        'wallet_balance',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function developedGames(): HasMany
    {
        return $this->hasMany(Game::class, 'developer_id');
    }

    public function library(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_user')
            ->withPivot('acquired_at', 'playtime_minutes', 'last_played_at', 'is_installed')
            ->withTimestamps();
    }

    public function hasRole(string $slug): bool
    {
        return $this->role && $this->role->slug === $slug;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isDeveloper(): bool
    {
        return $this->hasRole('developer');
    }

    public function isSupport(): bool
    {
        return $this->hasRole('support');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }
}
