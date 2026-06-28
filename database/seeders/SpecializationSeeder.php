<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;
use App\Models\Trade;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            [1, 'Leak Repair', 'Fix leaking pipes and fixtures'],
            [1, 'Installation', 'Install new plumbing systems'],
            [1, 'Bathroom Remodeling', 'Complete bathroom updates'],
            [2, 'Wiring Installation', 'Install new electrical wiring'],
            [2, 'Circuit Breaker Repair', 'Repair circuit breakers'],
            [2, 'Lighting Installation', 'Install light fixtures'],
            [3, 'Custom Furniture', 'Build custom wooden furniture'],
            [3, 'Cabinet Making', 'Create custom cabinets'],
            [4, 'Interior Painting', 'Paint interior walls'],
            [4, 'Exterior Painting', 'Paint exterior surfaces'],
            [5, 'Furnace Repair', 'Repair heating systems'],
            [5, 'AC Installation', 'Install air conditioning'],
        ];

        foreach ($specializations as [$tradeId, $name, $desc]) {
            Specialization::create([
                'trade_id' => $tradeId,
                'name' => $name,
                'description' => $desc,
            ]);
        }
    }
}
