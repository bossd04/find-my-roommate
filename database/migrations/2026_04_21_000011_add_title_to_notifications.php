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
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('message');
            }
            if (!Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['title', 'message', 'data', 'read_at']);
        });
    }
};
