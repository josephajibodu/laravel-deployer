<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Database>
 */
class DatabaseFactory extends Factory
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
            'name' => $this->faker->slug(2),
            'status' => $this->faker->randomElement(['active', 'inactive', 'error']),
            'database_users' => [
                ['username' => 'forge', 'password' => $this->faker->password()],
                ['username' => 'app_user', 'password' => $this->faker->password()],
            ],
        ];
    }
}
