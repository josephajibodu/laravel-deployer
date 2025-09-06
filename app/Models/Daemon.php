<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Daemon extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'server_id',
        'command',
        'folder',
        'user',
        'configurations',
        'stop_signal',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'configurations' => 'array',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
