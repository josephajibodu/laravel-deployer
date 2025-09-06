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
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('frequency'); // e.g., 'daily', 'hourly', 'weekly'
            $table->timestamp('next_run_time')->nullable();
            $table->text('cron'); // Full cron expression
            $table->string('user')->default('forge'); // User to run the command
            $table->text('command');
            $table->string('status')->default('active'); // active, inactive, error
            $table->boolean('invalid_user')->default(false);
            $table->string('minute')->default('*');
            $table->string('hour')->default('*');
            $table->string('day')->default('*');
            $table->string('month')->default('*');
            $table->string('weekday')->default('*');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_jobs');
    }
};
