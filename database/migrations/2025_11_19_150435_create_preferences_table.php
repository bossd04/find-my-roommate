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
            $table->string('cleanliness_level')->nullable();
            $table->string('sleep_pattern')->nullable();
            $table->string('study_habit')->nullable();
            $table->string('noise_tolerance')->nullable();
            $table->decimal('min_budget', 10, 2)->nullable();
            $table->decimal('max_budget', 10, 2)->nullable();
            $table->json('hobbies')->nullable();
            $table->json('lifestyle_tags')->nullable();
            $table->string('smoking')->default('never');
            $table->string('pets')->default('none');
            $table->string('overnight_visitors')->default('with_notice');
            $table->string('schedule')->default('irregular');
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
