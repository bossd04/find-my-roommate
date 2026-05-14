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
        Schema::table('listing_images', function (Blueprint $table) {
            // Rename path to image_path to match the model
            if (Schema::hasColumn('listing_images', 'path')) {
                $table->renameColumn('path', 'image_path');
            }
            
            // Add missing columns
            if (!Schema::hasColumn('listing_images', 'caption')) {
                $table->string('caption')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('listing_images', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('caption');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            if (Schema::hasColumn('listing_images', 'image_path')) {
                $table->renameColumn('image_path', 'path');
            }
            $table->dropColumn(['caption', 'is_primary']);
        });
    }
};
