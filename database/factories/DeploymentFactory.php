<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deployment>
 */
class DeploymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'team_id' => \App\Models\Team::factory(),
            'site_id' => \App\Models\Site::factory(),
            'commit_branch' => $this->faker->randomElement(['main', 'master', 'develop']),
            'commit_url' => $this->faker->optional(0.8)->url(),
            'commit_hash' => $this->faker->optional(0.8)->sha1(),
            'commit_message' => $this->faker->optional(0.8)->sentence(),
            'status' => $this->faker->randomElement(['pending', 'running', 'successful', 'failed']),
            'total_time_taken' => $this->faker->optional(0.7)->numberBetween(30, 300),
            'type' => $this->faker->randomElement(['deployment', 'rollback']),
            'deployable_type' => 'site',
        ];
    }
}
