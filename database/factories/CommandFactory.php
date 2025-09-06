<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Command>
 */
class CommandFactory extends Factory
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
            'user_id' => \App\Models\User::factory(),
            'site_id' => \App\Models\Site::factory(),
            'command' => $this->faker->randomElement([
                'php artisan migrate',
                'composer install --no-dev',
                'npm run build',
                'php artisan config:cache',
            ]),
            'status' => $this->faker->randomElement(['pending', 'running', 'successful', 'failed']),
            'duration' => $this->faker->optional(0.7)->randomElement(['30s', '1m 30s', '2m 15s']),
            'output' => $this->faker->optional(0.8)->text(500),
        ];
    }
}
