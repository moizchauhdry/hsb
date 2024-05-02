<?php

namespace Database\Seeders;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create(
            [
                'user_name' => 'Customer 01',
                'user_email' => 'customer1@hampton.com',
                'user_phone' => '1234567890-1',
                'pickup' => 'Model Town, Lahore',
                'destination' => 'Johar Town, Lahore',
                'price' => 100,
                'booking_date' => Carbon::now(),
            ]
        );
    }
}
