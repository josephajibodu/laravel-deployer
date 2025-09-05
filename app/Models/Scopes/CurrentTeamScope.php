<?php

namespace App\Models\Scopes;

use App\Helpers\TeamContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentTeamScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $teamId = TeamContext::getCurrentTeamId();

        if ($teamId === null) {
            // No valid team context - deny access to prevent data leakage
            $builder->whereRaw('1 = 0'); // Always false condition

            return;
        }

        // Apply team filter
        $builder->where('team_id', $teamId);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     */
    public function remove(Builder $builder, Model $model): void
    {
        $query = $builder->getQuery();

        foreach ($query->wheres as $key => $where) {
            if ($where['column'] === 'team_id' && $where['operator'] === '=') {
                unset($query->wheres[$key]);
            }
        }

        $query->wheres = array_values($query->wheres);
    }
}
