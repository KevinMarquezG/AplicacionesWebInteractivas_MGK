<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'developer_id',
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'cover_image',
        'banner_image',
        'executable_name',
        'download_url',
        'version',
        'status',
        'release_date',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'release_date' => 'date',
        ];
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_user')
            ->withPivot('acquired_at', 'playtime_minutes', 'is_installed');
    }

    public function isFree(): bool
    {
        return (float) $this->price === 0.00;
    }
}
