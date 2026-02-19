<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite compatibility, we need to use raw SQL to modify the column
        if (config('database.default') === 'sqlite') {
            // SQLite doesn't support modifying columns directly, so we need to recreate the table
            DB::statement('PRAGMA foreign_keys=off;');
            
            // Create a temporary table
            Schema::create('messages_temp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->unsignedBigInteger('sender_id');
                $table->unsignedBigInteger('receiver_id');
                $table->text('content');
                $table->timestamp('read_at')->nullable();
                $table->boolean('is_delivered')->default(false);
                $table->boolean('is_read')->default(false);
                $table->string('type')->default('text');
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->foreign('conversation_id')
                      ->references('id')
                      ->on('conversations')
                      ->onDelete('cascade');
                      
                $table->foreign('sender_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
                      
                $table->foreign('receiver_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
            
            // Copy data to temporary table with explicit column mapping
            $columns = [
                'id',
                'sender_id',
                'receiver_id',
                'content',
                'read_at',
                'created_at',
                'updated_at',
                'conversation_id',
                'is_delivered',
                'is_read',
                'type',
                'metadata'
            ];
            
            $columnsStr = implode(', ', $columns);
            DB::statement("INSERT INTO messages_temp ({$columnsStr}) SELECT {$columnsStr} FROM messages;");
            
            // Drop old table
            Schema::dropIfExists('messages');
            
            // Rename temporary table
            Schema::rename('messages_temp', 'messages');
            
            // Recreate indexes
            Schema::table('messages', function (Blueprint $table) {
                $table->index(['conversation_id', 'created_at']);
                $table->index(['sender_id', 'is_read']);
            });
            
            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // For other databases, use the standard approach
            Schema::table('messages', function (Blueprint $table) {
                $table->unsignedBigInteger('conversation_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
        // If you need to rollback, you'll need to restore from a backup
    }
};
