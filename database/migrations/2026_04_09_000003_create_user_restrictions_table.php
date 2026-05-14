<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restricted_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('restricted_by')->constrained('users')->onDelete('cascade');
            $table->integer('restriction_type'); // 1=limited_messaging, 2=no_new_matches, 3=read_only, 4=account_suspension
            $table->text('reason');
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['restricted_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_restrictions');
    }
};
