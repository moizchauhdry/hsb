<?php

namespace Database\Seeders;

use App\Models\PolicyRenewalStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RenewalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('policy_renewal_statuses')->truncate();

        $data = [
            [
                'name'  => 'Pending',
                'id' => 1,
            ],
            [
                'name'  => 'In Progress',
                'id' => 2,
            ],
            [
                'name'  => 'Missed',
                'id' => 3,
            ],
            [
                'name'  => 'Cancelled',
                'id' => 4,
            ]
        ];

        PolicyRenewalStatus::insert($data);
    }
}
