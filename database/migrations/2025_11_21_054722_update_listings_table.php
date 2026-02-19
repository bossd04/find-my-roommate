<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            // Rename and modify existing columns
            $table->renameColumn('min_price', 'price');
            $table->dropColumn('max_price');
            
            // Add new columns with default values to satisfy SQLite constraints
            $table->string('title')->default('Untitled Listing')->after('user_id');
            $table->integer('bedrooms')->default(1)->after('description');
            $table->integer('bathrooms')->default(1)->after('bedrooms');
            $table->string('property_type')->default('apartment')->after('bathrooms');
            $table->boolean('is_available')->default(true)->after('status');
            $table->decimal('area_sqft', 10, 2)->default(0)->after('is_available');
            $table->boolean('furnished')->default(false)->after('area_sqft');
            $table->boolean('utilities_included')->default(false)->after('furnished');
            $table->date('available_from')->nullable()->after('utilities_included');
            $table->integer('lease_duration_months')->default(12)->after('available_from');
            $table->decimal('security_deposit', 10, 2)->default(0)->after('lease_duration_months');
            $table->json('amenities')->nullable()->after('security_deposit');
            $table->text('house_rules')->nullable()->after('amenities');
            $table->string('image')->nullable()->after('house_rules');
            
            // Rename user_id to landlord_id to match relationship
            $table->renameColumn('user_id', 'landlord_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            // Revert column renames
            $table->renameColumn('price', 'min_price');
            $table->decimal('max_price', 10, 2)->after('min_price');
            $table->renameColumn('landlord_id', 'user_id');
            
            // Drop added columns
            $table->dropColumn([
                'title',
                'bedrooms',
                'bathrooms',
                'property_type',
                'is_available',
                'area_sqft',
                'furnished',
                'utilities_included',
                'available_from',
                'lease_duration_months',
                'security_deposit',
                'amenities',
                'house_rules',
                'image'
            ]);
        });
    }
};
