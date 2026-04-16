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
        Schema::create('user_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('target_user_id')->constrained('users')->onDelete('cascade');
            $table->integer('compatibility_score')->default(0);
            $table->integer('interaction_count')->default(0);
            $table->integer('profile_views')->default(0);
            $table->integer('messages_exchanged')->default(0);
            $table->integer('preference_matches')->default(0);
            $table->timestamp('last_interaction_at')->nullable();
            $table->boolean('is_fully_compatible')->default(false);
            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['user_id', 'target_user_id']);
            
            // Indexes for performance
            $table->index(['user_id', 'compatibility_score']);
            $table->index(['target_user_id', 'compatibility_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_compatibilities');
    }
};
