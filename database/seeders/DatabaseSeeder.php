<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->has(Message::factory()->count(1))
            ->create();

        User::factory([
            'name' => 'Abdulkadir Cemiloğlu',
            'username' => 'megasteve',
        ])
            ->has(Message::factory()->count(2))
            ->create();
    }
}
