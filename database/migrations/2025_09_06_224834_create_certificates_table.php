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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('activation_status')->default('pending');
            $table->boolean('active')->default(false);
            $table->string('status')->default('pending'); // pending, active, expired, error
            $table->string('folder_name')->nullable();
            $table->string('preferred_chain')->nullable();
            $table->string('key_type')->default('rsa');
            $table->string('request_status')->default('pending');
            $table->string('type')->default('letsencrypt'); // letsencrypt, custom
            $table->string('domain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
