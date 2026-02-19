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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('cleanliness_level', ['very_clean', 'clean', 'average', 'messy', 'very_messy']);
            $table->enum('sleep_pattern', ['early_bird', 'night_owl', 'flexible']);
            $table->enum('study_habit', ['quiet_environment', 'music_ok', 'tv_background', 'no_preference']);
            $table->enum('noise_tolerance', ['quiet', 'moderate', 'loud']);
            $table->decimal('min_budget', 10, 2)->nullable();
            $table->decimal('max_budget', 10, 2)->nullable();
            $table->json('hobbies')->nullable();
            $table->json('lifestyle_tags')->nullable();
            $table->enum('smoking', ['never', 'sometimes', 'regularly', 'only_outside'])->default('never');
            $table->enum('pets', ['none', 'cats_ok', 'dogs_ok', 'all_pets_ok', 'no_pets'])->default('none');
            $table->enum('overnight_visitors', ['not_allowed', 'with_notice', 'anytime'])->default('with_notice');
            $table->enum('schedule', ['morning', 'evening', 'night_shift', 'irregular'])->default('regular');
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
