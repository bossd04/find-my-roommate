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
        Schema::table('messages', function (Blueprint $table) {
            // Add conversation_id and make it required
            $table->unsignedBigInteger('conversation_id')->after('id');
            
            // Rename 'message' to 'content' for consistency
            $table->renameColumn('message', 'content');
            
            // Add new fields
            $table->boolean('is_delivered')->default(false)->after('content');
            $table->boolean('is_read')->default(false)->after('is_delivered');
            $table->string('type')->default('text')->after('is_read');
            $table->json('metadata')->nullable()->after('type');
            
            // Add foreign key constraint
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('conversations')
                  ->onDelete('cascade');
                  
            // Add index for better performance on common queries
            $table->index(['conversation_id', 'created_at']);
            $table->index(['sender_id', 'is_read']);
        });
        
        // Update existing messages to have a default conversation
        // This is a simplified approach - in a real app, you'd need to create conversations for existing messages
        if (Schema::hasTable('conversations')) {
            DB::statement('UPDATE messages SET conversation_id = 1 WHERE conversation_id IS NULL');
        }
        
        // For SQLite compatibility, we'll make conversation_id not nullable in a separate migration
        // that uses raw SQL if needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['conversation_id']);
            
            // Drop indexes
            $table->dropIndex(['conversation_id', 'created_at']);
            $table->dropIndex(['sender_id', 'is_read']);
            
            // Drop added columns
            $table->dropColumn(['conversation_id', 'is_delivered', 'is_read', 'type', 'metadata']);
            
            // Revert column name
            $table->renameColumn('content', 'message');
        });
    }
};
