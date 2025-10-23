<?php

namespace Tests\Feature;

use App\Models\ServerProvider;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_server_providers_index_page_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $response = $this->actingAs($user)->get('/server-providers');

        $response->assertStatus(200);
    }

    public function test_server_providers_create_page_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $response = $this->actingAs($user)->get('/server-providers/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_server_provider(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $response = $this->actingAs($user)->post('/server-providers', [
            'name' => 'Test DigitalOcean',
            'provider_type' => 'digitalocean',
            'credentials' => [
                'api_key' => 'test-api-key',
                'region' => 'nyc1',
            ],
        ]);

        $response->assertRedirect('/server-providers');
        $this->assertDatabaseHas('server_providers', [
            'name' => 'Test DigitalOcean',
            'provider_type' => 'digitalocean',
            'team_id' => $team->id,
        ]);
    }

    public function test_user_can_view_their_server_provider(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $serverProvider = ServerProvider::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)->get("/server-providers/{$serverProvider->id}");

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_teams_server_provider(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $otherTeam = Team::factory()->create();
        $serverProvider = ServerProvider::factory()->create([
            'team_id' => $otherTeam->id,
        ]);

        $response = $this->actingAs($user)->get("/server-providers/{$serverProvider->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_their_server_provider(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $serverProvider = ServerProvider::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)->put("/server-providers/{$serverProvider->id}", [
            'name' => 'Updated DigitalOcean',
            'provider_type' => 'digitalocean',
            'credentials' => [
                'api_key' => 'updated-api-key',
                'region' => 'sfo2',
            ],
            'is_active' => true,
        ]);

        $response->assertRedirect('/server-providers');
        $this->assertDatabaseHas('server_providers', [
            'id' => $serverProvider->id,
            'name' => 'Updated DigitalOcean',
        ]);
    }

    public function test_user_can_delete_their_server_provider(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        $serverProvider = ServerProvider::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)->delete("/server-providers/{$serverProvider->id}");

        $response->assertRedirect('/server-providers');
        $this->assertDatabaseMissing('server_providers', [
            'id' => $serverProvider->id,
        ]);
    }
}
