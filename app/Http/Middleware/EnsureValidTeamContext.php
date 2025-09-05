<?php

namespace App\Http\Middleware;

use App\Helpers\TeamContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidTeamContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-authenticated routes
        if (! Auth::check()) {
            return $next($request);
        }

        // Ensure user has a valid team context
        $team = TeamContext::getCurrentTeam();

        if (! $team) {
            // Redirect to team selection or show error
            return redirect()->route('teams.select')->with('error', 'Please select a valid team.');
        }

        return $next($request);
    }
}
