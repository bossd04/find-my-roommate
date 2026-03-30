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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('cascade');
            }
            if (!Schema::hasColumn('messages', 'message_type')) {
                $table->string('message_type')->default('text')->after('content');
            }
            if (!Schema::hasColumn('messages', 'delivery_status')) {
                $table->string('delivery_status')->default('sent')->after('message_type');
            }
            if (!Schema::hasColumn('messages', 'is_delivered')) {
                $table->boolean('is_delivered')->default(false)->after('delivery_status');
            }
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('is_delivered');
            }
            if (!Schema::hasColumn('messages', 'metadata')) {
                $table->text('metadata')->nullable()->after('is_read');
            }
            if (!Schema::hasColumn('messages', 'reaction')) {
                $table->string('reaction')->nullable()->after('metadata');
            }
            
            // Add missing indexes only if they don't exist
            if (!Schema::hasIndex('messages', 'messages_conversation_id_created_at_index')) {
                $table->index(['conversation_id', 'created_at']);
            }
            if (!Schema::hasIndex('messages', 'messages_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
};
