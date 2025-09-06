<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
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
            'server_provider_id' => \App\Models\ServerProvider::factory(),
            'name' => $this->faker->slug(2).'-server',
            'ip_address' => $this->faker->ipv4(),
            'private_ip_address' => $this->faker->optional(0.7)->ipv4(),
            'user' => 'forge',
            'path' => '/home/forge',
            'provider' => $this->faker->randomElement(['DigitalOcean', 'AWS', 'Linode', 'Vultr']),
            'server_type' => $this->faker->randomElement(['app', 'database', 'cache', 'worker']),
            'region' => $this->faker->randomElement(['nyc1', 'sfo2', 'fra1', 'sgp1']),
            'operating_system' => 'Ubuntu 24.04',
            'connection_status' => $this->faker->randomElement(['connected', 'disconnected', 'error']),
            'connection_status_updated_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 week', 'now'),
            'connection_status_output' => $this->faker->optional(0.3)->text(200),
            'public_key' => $this->faker->optional(0.9)->sha256(),
            'ssh_keys' => $this->faker->optional(0.6)->randomElements(['key1', 'key2', 'key3'], 2),
            'provisioned' => $this->faker->boolean(70),
            'credentials' => [
                'username' => 'forge',
                'port' => 22,
            ],
            'php_versions' => ['8.1', '8.2', '8.3'],
            'php_version' => $this->faker->randomElement(['8.1', '8.2', '8.3']),
        ];
    }
}
