<?php

namespace Database\Seeders;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->truncate();

        $departments = [
            [
                'id'  => 1,
                'code'  => 1,
                'name'  => 'Motor Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 2,
                'code'  => 2,
                'name'  => 'Miscellaneous',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 3,
                'code'  => 3,
                'name'  => 'Marine Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 4,
                'code'  => 4,
                'name'  => 'Engineering',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 5,
                'code'  => 5,
                'name'  => 'Life Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 6,
                'code'  => 6,
                'name'  => 'Fire Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 7,
                'code'  => 7,
                'name'  => 'Health Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 8,
                'code'  => 8,
                'name'  => 'All Risk Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 9,
                'code'  => 9,
                'name'  => 'Accident & Liability',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 10,
                'code'  => 10,
                'name'  => 'Money Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 11,
                'code'  => 11,
                'name'  => 'Property Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'  => 12,
                'code'  => 12,
                'name'  => 'Travel Insurance',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        Department::insert($departments);
    }
}
