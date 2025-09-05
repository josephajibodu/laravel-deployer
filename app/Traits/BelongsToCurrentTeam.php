<?php

namespace App\Traits;

use App\Models\Scopes\CurrentTeamScope;

trait BelongsToCurrentTeam
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTeamScope);
    }
}
