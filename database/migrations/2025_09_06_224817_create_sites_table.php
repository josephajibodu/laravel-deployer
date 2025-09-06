<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Domain name or site identifier
            $table->string('repository')->nullable(); // Git repository URL
            $table->string('repository_status')->default('not_connected');
            $table->string('repository_provider')->nullable(); // github, gitlab, bitbucket
            $table->string('repository_branch')->default('main');
            $table->string('deployment_status')->default('never_deployed');
            $table->string('php_version')->default('8.3');
            $table->boolean('isolated')->default(false);
            $table->string('user')->default('forge'); // Site user
            $table->string('root_path')->default('/home/forge'); // Site root path
            $table->string('web_directory')->default('public'); // Web directory
            $table->boolean('quick_deploy')->default(false);
            $table->timestamp('last_deployment_at')->nullable();
            $table->string('last_deployment_status')->nullable();
            $table->string('wildcards')->nullable(); // Wildcard domains
            $table->string('project_type')->default('laravel'); // laravel, static, etc.
            $table->string('full_path')->nullable(); // Full site path
            $table->string('database_name')->nullable(); // Associated database
            $table->boolean('https_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
