<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('insurances')->truncate();

        $insurances = [
            ['name'  => 'IGI General Insurance Limited'],
            ['name'  => 'Habib General Insurance Limited'],
            ['name'  => 'Jubilee General Insurance Limited'],
            ['name'  => 'Chubb Insurance Pakistan Limited'],
            ['name'  => 'Atlas Insurance Limited'],
            ['name'  => 'IGI Life Insurance'],
            ['name'  => 'Jubilee Life Insurance'],
            ['name'  => 'EFU General Insurance Limited'],
            ['name'  => 'EFU Life Assurance'],
            ['name'  => 'UBL Insurer Limited'],
            ['name'  => 'Askari General Insurance Co. Ltd'],
            ['name'  => 'Pak-Qatar Takaful'],
            ['name'  => 'Salaam Takaful Limited']
        ];

        foreach ($insurances as $insurance) {
            Insurance::create($insurance);
        }
    }
}
