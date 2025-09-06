<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'server_provider_id',
        'name',
        'ip_address',
        'private_ip_address',
        'user',
        'path',
        'provider',
        'server_type',
        'region',
        'operating_system',
        'connection_status',
        'connection_status_updated_at',
        'connection_status_output',
        'public_key',
        'ssh_keys',
        'provisioned',
        'credentials',
        'php_versions',
        'php_version',
    ];

    protected function casts(): array
    {
        return [
            'connection_status_updated_at' => 'datetime',
            'ssh_keys' => 'array',
            'provisioned' => 'boolean',
            'credentials' => 'array',
            'php_versions' => 'array',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function serverProvider(): BelongsTo
    {
        return $this->belongsTo(ServerProvider::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function databases(): HasMany
    {
        return $this->hasMany(Database::class);
    }

    public function cronJobs(): HasMany
    {
        return $this->hasMany(CronJob::class);
    }

    public function daemons(): HasMany
    {
        return $this->hasMany(Daemon::class);
    }
}
