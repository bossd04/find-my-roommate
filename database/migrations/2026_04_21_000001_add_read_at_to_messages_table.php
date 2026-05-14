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
            if (!Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('content');
            }
            if (!Schema::hasColumn('messages', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('conversation_id');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('messages', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id')->nullable()->after('sender_id');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('messages', 'is_delivered')) {
                $table->boolean('is_delivered')->default(false)->after('read_at');
            }
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('is_delivered');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['read_at', 'is_delivered', 'is_read']);
        });
    }
};
