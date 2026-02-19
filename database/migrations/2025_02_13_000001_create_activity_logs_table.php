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
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject', 'subject');
                $table->string('event')->nullable();
                $table->json('properties')->nullable();
                $table->foreignId('causer_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('causer_type')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->string('method')->nullable();
                $table->string('route')->nullable();
                $table->timestamps();
                
                $table->index(['causer_id', 'causer_type']);
                $table->index('log_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
