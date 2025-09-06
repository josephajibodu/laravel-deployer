<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SshKey>
 */
class SshKeyFactory extends Factory
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
            'name' => $this->faker->word().'-key',
            'public_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC'.$this->faker->sha1().' user@example.com',
            'private_key' => $this->faker->optional(0.3)->text(500),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
