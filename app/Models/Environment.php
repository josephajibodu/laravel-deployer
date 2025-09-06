<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Environment extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'site_id',
        'has_error',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'has_error' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
