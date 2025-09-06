<?php

namespace App\Models;

use App\Events\TeamCreated;
use App\Events\TeamDeleted;
use App\Events\TeamUpdated;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'personal_team',
    ];

    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    /**
     * Helper methods
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allUsers(): Collection
    {
        return $this->users->merge([$this->owner]);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Membership::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    public function hasUser(User $user): bool
    {
        return $this->users->contains($user) || $user->ownsTeam($this);
    }

    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    public function userHasPermission($user, $permission): bool
    {
        return $user->hasTeamPermission($this, $permission);
    }

    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function serverProviders(): HasMany
    {
        return $this->hasMany(ServerProvider::class);
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }

    public function sshKeys(): HasMany
    {
        return $this->hasMany(SshKey::class);
    }

    public function sourceControls(): HasMany
    {
        return $this->hasMany(SourceControl::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
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

    public function removeUser(User $user): void
    {
        if ($user->current_team_id === $this->id) {
            $user->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    public function purge(): void
    {
        $this->owner()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
