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
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('listings', 'bedrooms')) {
                $table->integer('bedrooms')->nullable()->after('location');
            }
            if (!Schema::hasColumn('listings', 'bathrooms')) {
                $table->integer('bathrooms')->nullable()->after('bedrooms');
            }
            if (!Schema::hasColumn('listings', 'area_sqft')) {
                $table->decimal('area_sqft', 10, 2)->nullable()->after('bathrooms');
            }
            if (!Schema::hasColumn('listings', 'available_from')) {
                $table->date('available_from')->nullable()->after('area_sqft');
            }
            if (!Schema::hasColumn('listings', 'lease_duration_months')) {
                $table->integer('lease_duration_months')->nullable()->after('available_from');
            }
            if (!Schema::hasColumn('listings', 'security_deposit')) {
                $table->decimal('security_deposit', 10, 2)->nullable()->after('lease_duration_months');
            }
            if (!Schema::hasColumn('listings', 'house_rules')) {
                $table->text('house_rules')->nullable()->after('security_deposit');
            }
            if (!Schema::hasColumn('listings', 'amenities')) {
                $table->json('amenities')->nullable()->after('house_rules');
            }
            if (!Schema::hasColumn('listings', 'furnished')) {
                $table->boolean('furnished')->default(false)->after('is_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['title', 'bedrooms', 'bathrooms', 'area_sqft', 'available_from', 'lease_duration_months', 'security_deposit', 'house_rules', 'amenities', 'furnished']);
        });
    }
};
