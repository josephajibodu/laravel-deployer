<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = ['server_created', 'site_deployed', 'certificate_issued', 'user_invited', 'deployment_failed'];
        $eventType = $this->faker->randomElement($eventTypes);

        return [
            'team_id' => \App\Models\Team::factory(),
            'user_id' => \App\Models\User::factory(),
            'event_type' => $eventType,
            'description' => $this->faker->sentence(),
            'properties' => [
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ],
            'subject_type' => $this->faker->optional(0.7)->randomElement(['App\\Models\\Server', 'App\\Models\\Site']),
            'subject_id' => $this->faker->optional(0.7)->numberBetween(1, 100),
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
