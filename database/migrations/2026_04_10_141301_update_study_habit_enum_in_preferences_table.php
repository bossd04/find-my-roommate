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
        Schema::table('preferences', function (Blueprint $table) {
            // Change lifestyle columns to string to avoid strict enum constraints mismatches
            $table->string('cleanliness_level')->nullable()->change();
            $table->string('sleep_pattern')->nullable()->change();
            $table->string('study_habit')->nullable()->change();
            $table->string('noise_tolerance')->nullable()->change();
            $table->string('schedule')->nullable()->change();
            $table->string('smoking')->nullable()->change();
            $table->string('pets')->nullable()->change();
            $table->string('overnight_visitors')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preferences', function (Blueprint $table) {
            // Reverting back to original enums if possible
            $table->enum('cleanliness_level', ['very_clean', 'clean', 'average', 'messy', 'very_messy'])->change();
            $table->enum('sleep_pattern', ['early_bird', 'night_owl', 'flexible'])->change();
            $table->enum('study_habit', ['quiet_environment', 'music_ok', 'tv_background', 'no_preference'])->change();
            $table->enum('noise_tolerance', ['quiet', 'moderate', 'loud'])->change();
        });
    }
};
