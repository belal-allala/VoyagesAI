<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BusLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = Bus::all();

        foreach ($buses as $bus) {
            BusLocation::create([
                'bus_id' => $bus->id,
                'latitude' => fake()->latitude(45, 50),
                'longitude' => fake()->longitude(2, 7),
                'timestamp' => Carbon::now()->subMinutes(rand(0, 60)),
            ]);
        }
    }
}