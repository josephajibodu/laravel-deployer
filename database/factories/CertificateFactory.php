<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
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
            'activation_status' => $this->faker->randomElement(['pending', 'active', 'expired']),
            'active' => $this->faker->boolean(70),
            'status' => $this->faker->randomElement(['pending', 'active', 'expired', 'error']),
            'folder_name' => $this->faker->optional(0.6)->slug(2),
            'preferred_chain' => $this->faker->optional(0.4)->randomElement(['ISRG Root X1', 'DST Root CA X3']),
            'key_type' => $this->faker->randomElement(['rsa', 'ecdsa']),
            'request_status' => $this->faker->randomElement(['pending', 'issued', 'failed']),
            'type' => $this->faker->randomElement(['letsencrypt', 'custom']),
            'domain' => $this->faker->domainName(),
        ];
    }
}
