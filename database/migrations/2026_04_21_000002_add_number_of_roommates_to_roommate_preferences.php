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
        Schema::table('roommate_preferences', function (Blueprint $table) {
            if (!Schema::hasColumn('roommate_preferences', 'number_of_roommates')) {
                $table->integer('number_of_roommates')->default(1)->after('preferred_gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roommate_preferences', function (Blueprint $table) {
            $table->dropColumn('number_of_roommates');
        });
    }
};
