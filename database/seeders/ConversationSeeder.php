<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conversation;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Conversation::firstOrCreate(['id' => 1], [
            'title' => 'Chat Assistant',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
