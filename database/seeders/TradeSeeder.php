<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trade;

class TradeSeeder extends Seeder
{
    public function run(): void
    {
        $trades = [
            ['name' => 'Plumbing', 'description' => 'Plumbing services', 'icon' => '🔧'],
            ['name' => 'Electrical', 'description' => 'Electrical services', 'icon' => '⚡'],
            ['name' => 'Carpentry', 'description' => 'Carpentry and woodwork', 'icon' => '🪵'],
            ['name' => 'Painting', 'description' => 'Painting services', 'icon' => '🎨'],
            ['name' => 'HVAC', 'description' => 'Heating, ventilation, AC', 'icon' => '❄️'],
            ['name' => 'Masonry', 'description' => 'Bricklaying and stonework', 'icon' => '🧱'],
            ['name' => 'Landscaping', 'description' => 'Landscaping and gardening', 'icon' => '🌳'],
            ['name' => 'Roofing', 'description' => 'Roofing services', 'icon' => '🏠'],
        ];

        foreach ($trades as $trade) {
            Trade::create($trade);
        }
    }
}
