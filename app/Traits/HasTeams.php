<?php

namespace App\Traits;

use App\Helpers\TeamContext;
use App\Models\Membership;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasTeams
{
    /**
     * Determine if the given team is the current team.
     */
    public function isCurrentTeam($team): bool
    {
        return $team->id === $this->currentTeam->id;
    }

    /**
     * Get the current team of the user's context.
     */
    public function currentTeam(): BelongsTo
    {
        if (is_null($this->current_team_id) && $this->id) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Switch the user's context to the given team.
     */
    public function switchTeam($team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        // Clear team context cache when switching teams
        TeamContext::clearCache($this);

        return true;
    }

    /**
     * Get all the teams the user owns or belongs to.
     */
    public function allTeams(): Collection
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }

    /**
     * Get all the teams the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get all the teams the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, Membership::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Get the user's "personal" team.
     */
    public function personalTeam(): Team
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(mixed $team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->id == $team->{$this->getForeignKey()};
    }

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(mixed $team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->ownsTeam($team) || $this->teams->contains(function ($t) use ($team) {
            return $t->id === $team->id;
        });
    }

    /**
     * Get the role that the user has on the team.
     */
    public function teamRole(mixed $team): ?string
    {
        if ($this->ownsTeam($team)) {
            return 'owner';
        }

        if (! $this->belongsToTeam($team)) {
            return null;
        }

        $membership = $team->users
            ->where('id', $this->id)
            ->first()
            ?->membership;

        return $membership?->role;
    }

    /**
     * Determine if the user has the given role on the given team.
     */
    public function hasTeamRole(mixed $team, string $role): bool
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $membership = $team->users
            ->where('id', $this->id)
            ->first()
            ?->membership;

        return $membership?->role === $role;
    }

    /**
     * Get the user's permissions for the given team.
     *
     * @param  mixed  $team
     */
    public function teamPermissions($team): array
    {
        if ($this->ownsTeam($team)) {
            return ['*'];
        }

        if (! $this->belongsToTeam($team)) {
            return [];
        }

        // Simple permission system - owners have all permissions
        $role = $this->teamRole($team);

        return $role === 'owner' ? ['*'] : [];
    }

    /**
     * Determine if the user has the given permission on the given team.
     *
     * @param  mixed  $team
     */
    public function hasTeamPermission($team, string $permission): bool
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $permissions = $this->teamPermissions($team);

        return in_array($permission, $permissions) ||
            in_array('*', $permissions) ||
            (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
            (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
    }
}
