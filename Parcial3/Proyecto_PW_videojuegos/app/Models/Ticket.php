<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_to',
        'game_id',
        'category',
        'subject',
        'message',
        'priority',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedSupport(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}