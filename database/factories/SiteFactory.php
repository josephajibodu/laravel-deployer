<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domain = $this->faker->domainName();
        $repository = $this->faker->optional(0.8)->userName().'/'.$this->faker->slug(1);

        return [
            'server_id' => \App\Models\Server::factory(),
            'name' => $domain,
            'repository' => $repository ? "https://github.com/{$repository}.git" : null,
            'repository_status' => $this->faker->randomElement(['connected', 'not_connected', 'error']),
            'repository_provider' => $this->faker->randomElement(['github', 'gitlab', 'bitbucket']),
            'repository_branch' => $this->faker->randomElement(['main', 'master', 'develop']),
            'deployment_status' => $this->faker->randomElement(['never_deployed', 'deployed', 'deploying', 'failed']),
            'php_version' => $this->faker->randomElement(['8.1', '8.2', '8.3']),
            'isolated' => $this->faker->boolean(20),
            'user' => 'forge',
            'root_path' => '/home/forge',
            'web_directory' => 'public',
            'quick_deploy' => $this->faker->boolean(60),
            'last_deployment_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'last_deployment_status' => $this->faker->optional(0.7)->randomElement(['successful', 'failed']),
            'wildcards' => $this->faker->optional(0.3)->domainName(),
            'project_type' => $this->faker->randomElement(['laravel', 'static', 'node', 'php']),
            'full_path' => "/home/forge/{$domain}",
            'database_name' => $this->faker->optional(0.6)->slug(2),
            'https_enabled' => $this->faker->boolean(70),
        ];
    }
}
