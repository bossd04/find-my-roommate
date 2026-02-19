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
        Schema::create('roommate_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic preferences
            $table->enum('preferred_gender', ['male', 'female', 'other', 'no_preference'])->default('no_preference');
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            
            // Lifestyle preferences
            $table->enum('preferred_cleanliness', ['very_messy', 'somewhat_messy', 'average', 'somewhat_clean', 'very_clean', 'no_preference'])->default('no_preference');
            $table->enum('preferred_noise_level', ['very_quiet', 'quiet', 'moderate', 'lively', 'very_loud', 'no_preference'])->default('no_preference');
            $table->enum('preferred_schedule', ['morning_person', 'night_owl', 'flexible', 'irregular', 'no_preference'])->default('no_preference');
            
            // Living situation
            $table->boolean('smoking_ok')->default(false);
            $table->boolean('pets_ok')->default(false);
            $table->boolean('has_apartment_preferred')->nullable();
            $table->string('preferred_location')->nullable();
            
            // Budget and timing
            $table->decimal('min_budget', 10, 2)->nullable();
            $table->decimal('max_budget', 10, 2)->nullable();
            $table->string('preferred_move_in_date')->nullable();
            $table->string('preferred_lease_duration')->nullable();
            
            // Additional preferences
            $table->boolean('willing_to_share_room')->default(false);
            $table->boolean('furnished_preferred')->nullable();
            $table->boolean('utilities_included_preferred')->nullable();
            $table->string('preferred_room_type')->nullable(); // private, shared, no_preference
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roommate_preferences');
    }
};
