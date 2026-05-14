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
        // SQLite doesn't support modifying enum columns directly
        // We need to recreate the table without the enum constraint
        
        // 1. Create a new table with string type instead of enum
        Schema::create('user_validations_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_type'); // Changed from enum to string
            $table->string('id_number');
            $table->string('id_front_image')->nullable();
            $table->string('id_back_image')->nullable();
            $table->string('status')->default('pending'); // Changed from enum to string
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 2. Copy data from old table if it exists
        if (Schema::hasTable('user_validations')) {
            $existingData = \DB::table('user_validations')->get();
            foreach ($existingData as $row) {
                \DB::table('user_validations_new')->insert((array)$row);
            }
            
            // 3. Drop old table
            Schema::dropIfExists('user_validations');
        }

        // 4. Rename new table to original name
        Schema::rename('user_validations_new', 'user_validations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot easily revert back to enum in SQLite
        // This is a one-way fix for SQLite compatibility
    }
};
