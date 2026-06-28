<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TradeSeeder::class,
            LanguageSeeder::class,
            SpecializationSeeder::class,
            AdminSeeder::class,
            TaskerSeeder::class,
        ]);
    }
}
