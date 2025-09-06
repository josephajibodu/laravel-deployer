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
        Schema::create('daemons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->text('command')->nullable();
            $table->string('folder')->nullable(); // Working directory
            $table->string('user')->default('forge'); // User to run the daemon
            $table->json('configurations')->nullable(); // Daemon configurations
            $table->string('stop_signal')->default('TERM'); // Signal to stop daemon
            $table->string('status')->default('stopped'); // running, stopped, error
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daemons');
    }
};
