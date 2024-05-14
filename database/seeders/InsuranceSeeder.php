<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Insert some stuff
       Insurance::upsert(array(
        [
            'id'    => 1,
            'name'  => 'Insurance 1',
            'company_logo' => '',
        ],
        [
            'id'    => 2,
            'name'  => 'Insurance 2',
            'company_logo' => '',
        ],
    ),['name'],['id','name','company_logo']);
    }
}
