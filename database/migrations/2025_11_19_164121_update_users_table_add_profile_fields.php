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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 10)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'university')) {
                $table->string('university')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course')->nullable()->after('university');
            }
            if (!Schema::hasColumn('users', 'year_level')) {
                $table->string('year_level', 50)->nullable()->after('course');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('year_level');
            }
            if (!Schema::hasColumn('users', 'budget_min')) {
                $table->decimal('budget_min', 10, 2)->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'budget_max')) {
                $table->decimal('budget_max', 10, 2)->nullable()->after('budget_min');
            }
            if (!Schema::hasColumn('users', 'hobbies')) {
                $table->json('hobbies')->nullable()->after('budget_max');
            }
            if (!Schema::hasColumn('users', 'lifestyle_tags')) {
                $table->json('lifestyle_tags')->nullable()->after('hobbies');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'age',
                'bio',
                'avatar',
                'university',
                'course',
                'year_level',
                'department',
                'budget_min',
                'budget_max',
                'hobbies',
                'lifestyle_tags'
            ]);
        });
    }
};
