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
            if (!Schema::hasColumn('roommate_profiles', 'city')) {
                $table->string('city')->nullable()->after('apartment_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roommate_profiles', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};
