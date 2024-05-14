<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert some stuff
        Agency::upsert(array(
            [
                'id'    => 1,
                'name'  => 'Agency 1',
                'code' => 'A1b23',
            ],
            [
                'id'    => 2,
                'name'  => 'Agency 2',
                'code' => 'A1b24',
            ],
        ),['name'],['id','name','code']);
    }
}
