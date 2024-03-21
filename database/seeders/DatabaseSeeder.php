<?php

namespace Database\Seeders;

// use Illuminate\Storage\Console\Seeds\WithoutModelEvents;
use App\Models\Resource;
use App\Models\TelegramUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TelegramUser::factory(100)->create()->each(function ($telegramUser) {
            Resource::factory(rand(5, 30))->for($telegramUser)->create();
        });
    }
}
