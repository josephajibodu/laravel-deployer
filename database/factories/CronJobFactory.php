<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CronJob>
 */
class CronJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $frequencies = ['daily', 'hourly', 'weekly', 'monthly'];
        $frequency = $this->faker->randomElement($frequencies);

        return [
            'team_id' => \App\Models\Team::factory(),
            'server_id' => \App\Models\Server::factory(),
            'frequency' => $frequency,
            'next_run_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'cron' => $this->faker->randomElement(['0 0 * * *', '0 * * * *', '0 0 * * 0', '0 0 1 * *']),
            'user' => 'forge',
            'command' => $this->faker->randomElement([
                'php artisan schedule:run',
                'php artisan queue:work',
                'php artisan backup:run',
            ]),
            'status' => $this->faker->randomElement(['active', 'inactive', 'error']),
            'invalid_user' => $this->faker->boolean(5),
            'minute' => $this->faker->randomElement(['0', '*', '*/5']),
            'hour' => $this->faker->randomElement(['0', '*', '2']),
            'day' => $this->faker->randomElement(['*', '1', '*/7']),
            'month' => $this->faker->randomElement(['*', '1', '*/3']),
            'weekday' => $this->faker->randomElement(['*', '0', '1-5']),
        ];
    }
}
