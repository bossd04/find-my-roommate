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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user1_id');
            $table->unsignedBigInteger('user2_id');
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedInteger('unread_count_user1')->default(0);
            $table->unsignedInteger('unread_count_user2')->default(0);
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('user1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure we don't have duplicate conversations
            $table->unique(['user1_id', 'user2_id']);
        });
        
        // For SQLite compatibility, we'll use a unique constraint on the sorted user IDs
        // This is handled in the application layer by ensuring user1_id < user2_id
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
