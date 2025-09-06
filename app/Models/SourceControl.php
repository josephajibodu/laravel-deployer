<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SourceControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'provider',
        'provider_id',
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
}
