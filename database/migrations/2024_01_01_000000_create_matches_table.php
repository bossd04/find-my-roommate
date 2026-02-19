<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('matched_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('user_action', ['liked', 'disliked'])->nullable();
            $table->boolean('is_mutual')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'matched_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
};
