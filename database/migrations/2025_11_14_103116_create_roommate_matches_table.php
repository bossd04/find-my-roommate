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
        if (!Schema::hasTable('roommate_matches')) {
            Schema::create('roommate_matches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('matched_user_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->enum('user_action', ['liked', 'disliked']);
                $table->boolean('is_mutual')->default(false);
                $table->timestamps();

                // Ensure a user can't have multiple matches with the same user
                $table->unique(['user_id', 'matched_user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roommate_matches');
    }
};
