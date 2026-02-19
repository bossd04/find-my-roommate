<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // First add the column as nullable
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            
            // Set a default user ID for existing records (e.g., first admin user)
            $defaultUserId = \App\Models\User::where('is_admin', true)->value('id') ?? 1;
            
            // Update existing records with the default user ID
            if (Schema::hasColumn('listings', 'user_id')) {
                \DB::table('listings')->update(['user_id' => $defaultUserId]);
                
                // Now make the column required
                $table->foreignId('user_id')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
