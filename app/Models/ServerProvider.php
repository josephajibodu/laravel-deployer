<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'provider_type',
        'credentials',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
