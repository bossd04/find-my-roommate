<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('user_blocks', 'blocked_at')) {
                $table->timestamp('blocked_at')->useCurrent()->after('reason');
            }
            if (!Schema::hasColumn('user_blocks', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('blocked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_blocks', function (Blueprint $table) {
            $table->dropColumn(['blocked_at', 'expires_at']);
        });
    }
};
