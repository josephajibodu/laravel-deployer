<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServerProvider>
 */
class ServerProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['digitalocean', 'aws', 'linode', 'vultr'];
        $provider = $this->faker->randomElement($providers);

        return [
            'team_id' => \App\Models\Team::factory(),
            'name' => ucfirst($provider),
            'provider_type' => $provider,
            'credentials' => [
                'api_key' => $this->faker->sha256(),
                'region' => $this->faker->randomElement(['nyc1', 'sfo2', 'fra1', 'sgp1']),
            ],
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
