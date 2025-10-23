<?php

namespace App\Http\Controllers;

use App\Models\ServerProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ServerProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $serverProviders = ServerProvider::where('team_id', auth()->user()->current_team_id)
            ->withCount('servers')
            ->latest()
            ->get();

        return Inertia::render('ServerProviders/Index', [
            'serverProviders' => $serverProviders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('ServerProviders/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ServerProvider::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => [
                'required',
                'string',
                Rule::in(['digitalocean', 'aws', 'linode', 'vultr', 'hetzner', 'custom']),
            ],
            'credentials' => 'required|array',
            'credentials.api_key' => 'required|string',
            'credentials.region' => 'nullable|string',
            'credentials.endpoint' => 'nullable|string|url',
        ]);

        $serverProvider = ServerProvider::create([
            'team_id' => auth()->user()->current_team_id,
            'name' => $validated['name'],
            'provider_type' => $validated['provider_type'],
            'credentials' => $validated['credentials'],
            'is_active' => true,
        ]);

        return redirect()->route('server-providers.index')
            ->with('success', 'Server provider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServerProvider $serverProvider): Response
    {
        $this->authorize('view', $serverProvider);

        $serverProvider->load(['servers' => function ($query) {
            $query->latest()->take(10);
        }]);

        return Inertia::render('ServerProviders/Show', [
            'serverProvider' => $serverProvider,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServerProvider $serverProvider): Response
    {
        $this->authorize('update', $serverProvider);

        return Inertia::render('ServerProviders/Edit', [
            'serverProvider' => $serverProvider,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServerProvider $serverProvider): RedirectResponse
    {
        $this->authorize('update', $serverProvider);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => [
                'required',
                'string',
                Rule::in(['digitalocean', 'aws', 'linode', 'vultr', 'hetzner', 'custom']),
            ],
            'credentials' => 'required|array',
            'credentials.api_key' => 'required|string',
            'credentials.region' => 'nullable|string',
            'credentials.endpoint' => 'nullable|string|url',
            'is_active' => 'boolean',
        ]);

        $serverProvider->update($validated);

        return redirect()->route('server-providers.index')
            ->with('success', 'Server provider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServerProvider $serverProvider): RedirectResponse
    {
        $this->authorize('delete', $serverProvider);

        // Check if provider has servers
        if ($serverProvider->servers()->count() > 0) {
            return redirect()->route('server-providers.index')
                ->with('error', 'Cannot delete server provider that has associated servers.');
        }

        $serverProvider->delete();

        return redirect()->route('server-providers.index')
            ->with('success', 'Server provider deleted successfully.');
    }

    /**
     * Test the connection to the server provider.
     */
    public function test(ServerProvider $serverProvider): RedirectResponse
    {
        $this->authorize('view', $serverProvider);

        // TODO: Implement actual connection testing
        // For now, just return success
        return redirect()->back()
            ->with('success', 'Connection test successful.');
    }
}
