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
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('causer_id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action')->nullable()->after('event');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'action']);
        });
    }
};
