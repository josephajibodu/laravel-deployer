<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Environment>
 */
class EnvironmentFactory extends Factory
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
            'site_id' => \App\Models\Site::factory(),
            'has_error' => $this->faker->boolean(10),
            'config' => 'APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=base64:'.$this->faker->sha256().'\nAPP_DEBUG=false\nAPP_URL=https://'.$this->faker->domainName(),
        ];
    }
}
