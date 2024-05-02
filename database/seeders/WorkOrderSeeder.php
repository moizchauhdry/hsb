<?php

namespace Database\Seeders;

use App\Models\WorkOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkOrder::create([
            'wo_title' => 'Change tire, tube and clean chain',
            'wo_customer_name' => 'Moiz Chauhdry',
            'wo_bike_brand' => 'Honda',
            'wo_bike_model' => 'YBR 125',
            'wo_date' => '2023-04-29',
            'wo_return_date' => '2023-05-01',
            'wo_completed_date' => '2023-05-01',
            'wo_grand_total' => 150.25,
        ]);
    }
}
