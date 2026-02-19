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
        Schema::table('roommate_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('roommate_profiles', 'study_habit')) {
                $table->enum('study_habit', ['intense', 'moderate', 'social', 'quiet'])->nullable()->after('sleep_pattern');
            }
            if (!Schema::hasColumn('roommate_profiles', 'noise_tolerance')) {
                $table->enum('noise_tolerance', ['quiet', 'moderate', 'loud'])->nullable()->after('study_habit');
            }
            // budget_min and budget_max already exist, so we don't need to add them
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roommate_profiles', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('roommate_profiles', 'study_habit')) {
                $columnsToDrop[] = 'study_habit';
            }
            if (Schema::hasColumn('roommate_profiles', 'noise_tolerance')) {
                $columnsToDrop[] = 'noise_tolerance';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
