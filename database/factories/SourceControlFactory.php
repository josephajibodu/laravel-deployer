<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourceControl>
 */
class SourceControlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['github', 'gitlab', 'bitbucket'];
        $provider = $this->faker->randomElement($providers);

        return [
            'team_id' => \App\Models\Team::factory(),
            'name' => ucfirst($provider).' Integration',
            'provider' => $provider,
            'provider_id' => $this->faker->optional(0.8)->numberBetween(1000, 9999),
            'credentials' => [
                'access_token' => $this->faker->sha256(),
                'refresh_token' => $this->faker->optional(0.5)->sha256(),
            ],
            'is_active' => $this->faker->boolean(85),
        ];
    }
}
