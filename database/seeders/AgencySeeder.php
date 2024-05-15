<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agencies')->truncate();

        $agencies = [
            [
                'name'  => 'Hellenic Sun Insurance Brokers (Pvt) Limited',
                'code' => 1,
            ],
            [
                'name'  => 'Aasma Daver',
                'code' => 2,
            ],
            [
                'name'  => 'Agha Jahanzeb Haider',
                'code' => 3,
            ],
            [
                'name'  => 'Maliha Zafar',
                'code' => 4,
            ],
            [
                'name'  => 'Maria Zafar Raza',
                'code' => 5,
            ],
            [
                'name'  => 'Mehreen Haider',
                'code' => 6,
            ],
            [
                'name'  => 'Mahvesh Hamed',
                'code' => 7,
            ],
            [
                'name'  => 'Neha Haider',
                'code' => 8,
            ],
            [
                'name'  => 'Rubbiha Imran',
                'code' => 9,
            ],
        ];

        foreach ($agencies as $agency) {
            Agency::create($agency);
        }
    }
}
