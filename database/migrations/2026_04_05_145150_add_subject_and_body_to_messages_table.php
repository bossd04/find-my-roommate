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
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'subject')) {
                if (Schema::hasColumn('messages', 'receiver_id')) {
                    $table->string('subject')->nullable()->after('receiver_id');
                } else {
                    $table->string('subject')->nullable();
                }
            }
            
            if (!Schema::hasColumn('messages', 'body')) {
                if (Schema::hasColumn('messages', 'subject')) {
                    $table->text('body')->nullable()->after('subject');
                } else {
                    $table->text('body')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['subject', 'body']);
        });
    }
};
