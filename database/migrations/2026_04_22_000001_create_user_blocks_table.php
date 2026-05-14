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
        if (!Schema::hasTable('user_blocks')) {
            Schema::create('user_blocks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('blocked_id')->constrained('users')->onDelete('cascade');
                $table->text('reason')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['blocker_id', 'blocked_id']);
                $table->index('expires_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_blocks');
    }
};
