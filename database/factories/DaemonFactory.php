<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Daemon>
 */
class DaemonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => \App\Models\Team::factory(),
            'server_id' => \App\Models\Server::factory(),
            'command' => $this->faker->randomElement([
                'php artisan queue:work --sleep=3 --tries=3',
                'php artisan horizon',
                'php artisan websockets:serve',
            ]),
            'folder' => $this->faker->optional(0.7)->randomElement(['/home/forge/app', '/home/forge/site']),
            'user' => 'forge',
            'configurations' => [
                'timeout' => 60,
                'memory' => 128,
                'tries' => 3,
            ],
            'stop_signal' => $this->faker->randomElement(['TERM', 'KILL', 'INT']),
            'status' => $this->faker->randomElement(['running', 'stopped', 'error']),
        ];
    }
}
