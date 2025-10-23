<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\ServerProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $servers = Server::where('team_id', auth()->user()->current_team_id)
            ->with(['serverProvider'])
            ->get();

        return Inertia::render('Servers/Index', [
            'servers' => $servers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Server::class);

        $serverProviders = ServerProvider::where('team_id', auth()->user()->current_team_id)
            ->where('is_active', true)
            ->get();

        return Inertia::render('Servers/Create', [
            'serverProviders' => $serverProviders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Server::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'server_provider_id' => 'required|exists:server_providers,id',
            'server_type' => ['required', 'string', Rule::in(['app', 'database', 'cache', 'worker', 'load_balancer'])],
            'region' => 'required|string|max:255',
            'server_size' => 'required|string|max:255',
            'operating_system' => 'nullable|string|max:255',
            'php_version' => 'nullable|string|max:10',
        ]);

        // Verify the server provider belongs to the user's team
        $serverProvider = ServerProvider::where('id', $validated['server_provider_id'])
            ->where('team_id', auth()->user()->current_team_id)
            ->firstOrFail();

        $server = Server::create([
            'team_id' => auth()->user()->current_team_id,
            'server_provider_id' => $validated['server_provider_id'],
            'name' => $validated['name'],
            'ip_address' => '0.0.0.0', // Placeholder until server is provisioned
            'server_type' => $validated['server_type'],
            'region' => $validated['region'],
            'operating_system' => $validated['operating_system'] ?? 'Ubuntu 24.04',
            'php_version' => $validated['php_version'] ?? '8.3',
            'connection_status' => 'pending',
            'provisioned' => false,
        ]);

        // In a real application, this would trigger server provisioning
        // For now, we'll simulate it by updating the status
        $server->update([
            'connection_status' => 'provisioning',
            'connection_status_updated_at' => now(),
        ]);

        return redirect()->route('servers.show', $server->id)
            ->with('success', 'Server created successfully and is being provisioned.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server): Response
    {
        $this->authorize('view', $server);

        $server->load(['serverProvider', 'sites', 'databases', 'cronJobs', 'daemons']);

        return Inertia::render('Servers/Show', [
            'server' => $server,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server): Response
    {
        $this->authorize('update', $server);

        $serverProviders = ServerProvider::where('team_id', auth()->user()->current_team_id)
            ->where('is_active', true)
            ->get();

        return Inertia::render('Servers/Edit', [
            'server' => $server,
            'serverProviders' => $serverProviders,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server): RedirectResponse
    {
        $this->authorize('update', $server);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'server_provider_id' => 'required|exists:server_providers,id',
            'server_type' => ['required', 'string', Rule::in(['app', 'database', 'cache', 'worker', 'load_balancer'])],
            'region' => 'required|string|max:255',
            'operating_system' => 'nullable|string|max:255',
            'php_version' => 'nullable|string|max:10',
        ]);

        // Verify the server provider belongs to the user's team
        $serverProvider = ServerProvider::where('id', $validated['server_provider_id'])
            ->where('team_id', auth()->user()->current_team_id)
            ->firstOrFail();

        $server->update([
            'name' => $validated['name'],
            'server_provider_id' => $validated['server_provider_id'],
            'server_type' => $validated['server_type'],
            'region' => $validated['region'],
            'operating_system' => $validated['operating_system'] ?? $server->operating_system,
            'php_version' => $validated['php_version'] ?? $server->php_version,
        ]);

        return redirect()->route('servers.show', $server->id)
            ->with('success', 'Server updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server): RedirectResponse
    {
        $this->authorize('delete', $server);

        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', 'Server deleted successfully.');
    }

    /**
     * Test the connection to the server.
     */
    public function test(Server $server): RedirectResponse
    {
        $this->authorize('view', $server);

        // In a real application, this would test the server connection
        // For now, we'll simulate a success or failure
        $success = (bool) random_int(0, 1);

        if ($success) {
            $server->update([
                'connection_status' => 'connected',
                'connection_status_updated_at' => now(),
            ]);

            return back()->with('success', 'Server connection test successful!');
        } else {
            $server->update([
                'connection_status' => 'error',
                'connection_status_updated_at' => now(),
                'connection_status_output' => 'Connection failed: Unable to reach server',
            ]);

            return back()->with('error', 'Server connection test failed. Please check your server configuration.');
        }
    }
}
