<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = Bus::all();

        for ($i = 0; $i < 5; $i++) {
            $bus = $buses->random();

            Reservation::create([
                'bus_id' => $bus->id,
                'user_id' => null, 
                'passenger_name' => fake()->name(),
                'passenger_email' => fake()->email(),
                'seat_count' => rand(1, 4),
                'total_price' => rand(20, 100),
                'reservation_date' => Carbon::now()->addDays(rand(1, 30)),
                'status' => rand(0, 1) ? 'confirmed' : 'pending',
            ]);
        }
    }
}