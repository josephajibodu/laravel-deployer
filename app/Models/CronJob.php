<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CronJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'server_id',
        'frequency',
        'next_run_time',
        'cron',
        'user',
        'command',
        'status',
        'invalid_user',
        'minute',
        'hour',
        'day',
        'month',
        'weekday',
    ];

    protected function casts(): array
    {
        return [
            'next_run_time' => 'datetime',
            'invalid_user' => 'boolean',
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
