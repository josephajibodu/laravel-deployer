<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Command;
use App\Models\CronJob;
use App\Models\Daemon;
use App\Models\Database;
use App\Models\Deployment;
use App\Models\Environment;
use App\Models\Server;
use App\Models\ServerProvider;
use App\Models\Site;
use App\Models\SourceControl;
use App\Models\SshKey;
use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create a team for the user
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Team',
            'personal_team' => true,
        ]);

        // Update user's current team
        $user->update(['current_team_id' => $team->id]);

        // Create server providers
        $serverProviders = ServerProvider::factory(3)->create([
            'team_id' => $team->id,
        ]);

        // Create servers
        $servers = collect();
        foreach ($serverProviders as $provider) {
            $servers = $servers->merge(
                Server::factory(2)->create([
                    'team_id' => $team->id,
                    'server_provider_id' => $provider->id,
                ])
            );
        }

        // Create sites for each server
        $sites = collect();
        foreach ($servers as $server) {
            $sites = $sites->merge(
                Site::factory(2)->create([
                    'server_id' => $server->id,
                ])
            );
        }

        // Create some deployments
        foreach ($sites->take(5) as $site) {
            Deployment::factory(3)->create([
                'user_id' => $user->id,
                'team_id' => $team->id,
                'site_id' => $site->id,
            ]);
        }

        // Create SSH keys
        SshKey::factory(2)->create([
            'team_id' => $team->id,
        ]);

        // Create source control integrations
        SourceControl::factory(2)->create([
            'team_id' => $team->id,
        ]);

        // Create certificates for some sites
        foreach ($sites->take(3) as $site) {
            Certificate::factory()->create([
                'team_id' => $team->id,
                'user_id' => $user->id,
                'site_id' => $site->id,
            ]);
        }

        // Create environments for sites
        foreach ($sites as $site) {
            Environment::factory()->create([
                'team_id' => $team->id,
                'site_id' => $site->id,
            ]);
        }

        // Create databases for servers
        foreach ($servers as $server) {
            Database::factory()->create([
                'team_id' => $team->id,
                'server_id' => $server->id,
            ]);
        }

        // Create cron jobs for servers
        foreach ($servers as $server) {
            CronJob::factory(2)->create([
                'team_id' => $team->id,
                'server_id' => $server->id,
            ]);
        }

        // Create daemons for servers
        foreach ($servers as $server) {
            Daemon::factory()->create([
                'team_id' => $team->id,
                'server_id' => $server->id,
            ]);
        }

        // Create commands for sites
        foreach ($sites->take(3) as $site) {
            Command::factory(2)->create([
                'team_id' => $team->id,
                'user_id' => $user->id,
                'site_id' => $site->id,
            ]);
        }

        // Create activity logs
        ActivityLog::factory(10)->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
    }
}
