<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'name',
        'repository',
        'repository_status',
        'repository_provider',
        'repository_branch',
        'deployment_status',
        'php_version',
        'isolated',
        'user',
        'root_path',
        'web_directory',
        'quick_deploy',
        'last_deployment_at',
        'last_deployment_status',
        'wildcards',
        'project_type',
        'full_path',
        'database_name',
        'https_enabled',
    ];

    protected function casts(): array
    {
        return [
            'isolated' => 'boolean',
            'quick_deploy' => 'boolean',
            'last_deployment_at' => 'datetime',
            'https_enabled' => 'boolean',
        ];
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    public function commands(): HasMany
    {
        return $this->hasMany(Command::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }
}
