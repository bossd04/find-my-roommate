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
        if (!Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->after('last_name');
            });
            
            // Update existing records with first_name + last_name
            if (Schema::hasTable('users')) {
                $users = \DB::table('users')->get();
                foreach ($users as $user) {
                    if (isset($user->first_name) && isset($user->last_name)) {
                        \DB::table('users')
                            ->where('id', $user->id)
                            ->update(['name' => $user->first_name . ' ' . $user->last_name]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
