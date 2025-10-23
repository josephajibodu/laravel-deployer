<?php

use App\Models\Server;
use App\Models\ServerProvider;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('user can create server', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create([
        'user_id' => $user->id,
        'personal_team' => true,
    ]);
    $user->update(['current_team_id' => $team->id]);

    $serverProvider = ServerProvider::factory()->create([
        'team_id' => $team->id,
    ]);

    $response = $this->actingAs($user)->post('/servers', [
        'name' => 'Test Server',
        'server_provider_id' => $serverProvider->id,
        'server_type' => 'app',
        'region' => 'nyc1',
        'server_size' => 's-1vcpu-1gb',
        'operating_system' => 'Ubuntu 24.04',
        'php_version' => '8.3',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('servers', [
        'name' => 'Test Server',
        'team_id' => $team->id,
        'server_provider_id' => $serverProvider->id,
        'server_type' => 'app',
        'region' => 'nyc1',
    ]);
});

it('user can view servers index', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create([
        'user_id' => $user->id,
        'personal_team' => true,
    ]);
    $user->update(['current_team_id' => $team->id]);

    $server = Server::factory()->create([
        'team_id' => $team->id,
    ]);

    $response = $this->actingAs($user)->get('/servers');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Servers/Index')
        ->has('servers', 1)
        ->where('servers.0.name', $server->name)
    );
});

it('user can view server details', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create([
        'user_id' => $user->id,
        'personal_team' => true,
    ]);
    $user->update(['current_team_id' => $team->id]);

    $server = Server::factory()->create([
        'team_id' => $team->id,
    ]);

    $response = $this->actingAs($user)->get("/servers/{$server->id}");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Servers/Show')
        ->where('server.name', $server->name)
    );
});

it('user cannot view other teams servers', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create([
        'user_id' => $user->id,
        'personal_team' => true,
    ]);
    $user->update(['current_team_id' => $team->id]);

    $otherTeam = Team::factory()->create();
    $otherServer = Server::factory()->create([
        'team_id' => $otherTeam->id,
    ]);

    $response = $this->actingAs($user)->get("/servers/{$otherServer->id}");

    $response->assertForbidden();
});
