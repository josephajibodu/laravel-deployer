<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TeamContext
{
    private const CACHE_KEY = 'user_team_context_';

    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Get the current team context for the authenticated user.
     */
    public static function getCurrentTeam(): ?Team
    {
        if (! Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $cacheKey = self::CACHE_KEY.$user->id;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            return $user->currentTeam;
        });
    }

    /**
     * Clear the team context cache for a user.
     */
    public static function clearCache(User $user): void
    {
        Cache::forget(self::CACHE_KEY.$user->id);
    }

    /**
     * Verify if the current user can access the given team.
     */
    public static function canAccessTeam(Team $team): bool
    {
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        return $user->belongsToTeam($team);
    }

    /**
     * Get the current team ID safely.
     */
    public static function getCurrentTeamId(): ?int
    {
        $team = self::getCurrentTeam();

        return $team?->id;
    }
}
