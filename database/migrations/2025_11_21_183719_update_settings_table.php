<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('settings')) {
            // Drop the existing table if it's empty
            if (DB::table('settings')->count() === 0) {
                Schema::dropIfExists('settings');
                
                // Recreate the table with the correct structure
                Schema::create('settings', function (Blueprint $table) {
                    $table->id();
                    $table->string('key')->unique();
                    $table->text('value')->nullable();
                    $table->timestamps();
                });
            } else {
                // If table has data, add missing columns
                Schema::table('settings', function (Blueprint $table) {
                    if (!Schema::hasColumn('settings', 'key')) {
                        $table->string('key')->after('id');
                    }
                    if (!Schema::hasColumn('settings', 'value')) {
                        $table->text('value')->nullable()->after('key');
                    }
                    if (!Schema::hasColumn('settings', 'created_at')) {
                        $table->timestamps();
                    }
                });
            }
        } else {
            // Create the table if it doesn't exist
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // This is a safe down migration that won't drop the table
        // to prevent data loss
    }
};
