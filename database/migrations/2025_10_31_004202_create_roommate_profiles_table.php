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
        Schema::create('roommate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('display_name');
            $table->integer('age');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say']);
            $table->text('bio')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->enum('cleanliness_level', ['very_messy', 'somewhat_messy', 'average', 'somewhat_clean', 'very_clean']);
            $table->enum('noise_level', ['very_quiet', 'quiet', 'moderate', 'lively', 'very_loud']);
            $table->enum('schedule', ['morning_person', 'night_owl', 'flexible', 'irregular']);
            $table->boolean('smoking_allowed')->default(false);
            $table->boolean('pets_allowed')->default(false);
            $table->boolean('has_apartment')->default(false);
            $table->string('apartment_location')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->string('move_in_date')->nullable();
            $table->string('lease_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roommate_profiles');
    }
};
