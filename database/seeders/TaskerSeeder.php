<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Trade;
use App\Models\TaskerProfile;

class TaskerSeeder extends Seeder
{
    // Nairobi neighbourhoods with approximate centre coordinates
    private const HOODS = [
        ['name' => 'Westlands',    'lat' => -1.2664, 'lng' => 36.8026],
        ['name' => 'Kilimani',     'lat' => -1.2903, 'lng' => 36.7885],
        ['name' => 'Karen',        'lat' => -1.3214, 'lng' => 36.6912],
        ['name' => 'Kileleshwa',   'lat' => -1.2771, 'lng' => 36.7896],
        ['name' => 'Lavington',    'lat' => -1.2814, 'lng' => 36.7796],
        ['name' => 'Parklands',    'lat' => -1.2531, 'lng' => 36.8174],
        ['name' => 'Upper Hill',   'lat' => -1.2989, 'lng' => 36.8136],
        ['name' => 'CBD',          'lat' => -1.2833, 'lng' => 36.8167],
        ['name' => 'Eastleigh',    'lat' => -1.2715, 'lng' => 36.8440],
        ['name' => 'South C',      'lat' => -1.3150, 'lng' => 36.8267],
        ['name' => 'Langata',      'lat' => -1.3450, 'lng' => 36.7468],
        ['name' => 'Kasarani',     'lat' => -1.2233, 'lng' => 36.8856],
        ['name' => 'Embakasi',     'lat' => -1.3200, 'lng' => 36.9000],
        ['name' => 'Ruaka',        'lat' => -1.2111, 'lng' => 36.7889],
        ['name' => 'Thika Road',   'lat' => -1.2000, 'lng' => 36.8500],
        ['name' => 'South B',      'lat' => -1.3050, 'lng' => 36.8350],
        ['name' => 'Ngong Road',   'lat' => -1.3100, 'lng' => 36.7800],
        ['name' => 'Hurlingham',   'lat' => -1.2950, 'lng' => 36.7960],
        ['name' => 'Gigiri',       'lat' => -1.2350, 'lng' => 36.8020],
        ['name' => 'Runda',        'lat' => -1.2150, 'lng' => 36.8100],
    ];

    private const BIOS = [
        'Experienced professional with over 5 years in the field. Committed to quality workmanship and customer satisfaction.',
        'Reliable and skilled tradesperson. I take pride in every job and ensure the work is done right the first time.',
        'Certified professional offering competitive rates. Available on short notice for urgent jobs across Nairobi.',
        'Detail-oriented and punctual. I bring all necessary tools and clean up thoroughly after every job.',
        'Passionate about my craft with a growing portfolio of satisfied clients across Nairobi and its environs.',
        'Trained and insured. I specialise in both residential and commercial work with transparent pricing.',
        'Fast turnaround without compromising quality. Customer reviews speak for themselves — check my profile.',
        'Over a decade of hands-on experience. I assess the problem first and give honest, fair quotes.',
    ];

    public function run(): void
    {
        $trades = Trade::all();

        if ($trades->isEmpty()) {
            $this->command->warn('No trades found — run TradeSeeder first.');
            return;
        }

        $count = 60;

        for ($i = 0; $i < $count; $i++) {
            $hood = self::HOODS[array_rand(self::HOODS)];

            // Scatter up to ~1.5 km from the neighbourhood centre
            $lat = round($hood['lat'] + (mt_rand(-150, 150) / 10000), 6);
            $lng = round($hood['lng'] + (mt_rand(-150, 150) / 10000), 6);

            $trade         = $trades->random();
            $ratingCount   = mt_rand(0, 40);
            $averageRating = $ratingCount > 0 ? round(mt_rand(30, 50) / 10, 2) : 0;

            $user = User::create([
                'name'                => fake()->name(),
                'email'               => fake()->unique()->safeEmail(),
                'password'            => Hash::make('password'),
                'role'                => 'tasker',
                'latitude'            => $lat,
                'longitude'           => $lng,
                'email_verified_at'   => now()->subDays(mt_rand(1, 365)),
                'verification_status' => 'verified',
                'suspension_status'   => 'none',
                'phone'               => '+2547' . mt_rand(10000000, 99999999),
                'phone_verified_at'   => now()->subDays(mt_rand(1, 30)),
            ]);

            TaskerProfile::create([
                'user_id'          => $user->id,
                'trade_id'         => $trade->id,
                'hourly_rate'      => mt_rand(5, 50) * 100,   // 500 – 5 000 KES
                'fixed_rate'       => mt_rand(0, 1) ? mt_rand(10, 200) * 100 : null,
                'price_negotiable' => (bool) mt_rand(0, 1),
                'bio'              => self::BIOS[array_rand(self::BIOS)],
                'average_rating'   => $averageRating,
                'rating_count'     => $ratingCount,
                'is_promoted'      => mt_rand(0, 5) === 0, // ~17% promoted
            ]);
        }

        $this->command->info("Seeded {$count} taskers across Nairobi neighbourhoods.");
    }
}
