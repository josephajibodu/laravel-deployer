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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_provider_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('ip_address');
            $table->string('private_ip_address')->nullable();
            $table->string('user')->default('forge'); // SSH user
            $table->string('path')->default('/home/forge'); // Server path
            $table->string('provider')->nullable(); // Provider name
            $table->string('server_type')->default('app'); // app, database, cache, etc.
            $table->string('region')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('connection_status')->default('disconnected');
            $table->timestamp('connection_status_updated_at')->nullable();
            $table->text('connection_status_output')->nullable();
            $table->string('public_key')->nullable();
            $table->json('ssh_keys')->nullable();
            $table->boolean('provisioned')->default(false);
            $table->json('credentials')->nullable(); // Server-specific credentials
            $table->json('php_versions')->nullable(); // Available PHP versions
            $table->string('php_version')->default('8.3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
