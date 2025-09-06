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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('commit_branch')->nullable();
            $table->string('commit_url')->nullable();
            $table->string('commit_hash')->nullable();
            $table->text('commit_message')->nullable();
            $table->string('status')->default('pending'); // pending, running, successful, failed
            $table->integer('total_time_taken')->nullable(); // in seconds
            $table->string('type')->default('deployment'); // deployment, rollback, etc.
            $table->string('deployable_type')->default('site'); // site, server, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
