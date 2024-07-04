<?php

namespace Database\Seeders;

use App\Models\Department;
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
                'name'  => 'Motor',
            ],
            [
                'id'  => 2,
                'name'  => 'Miscellaneous',
            ],
            [
                'id'  => 3,
                'name'  => 'Marine',
            ],
            [
                'id'  => 4,
                'name'  => 'Engineering',
            ],
            [
                'id'  => 5,
                'name'  => 'Life',
            ],
            [
                'id'  => 6,
                'name'  => 'Fire',
            ],
            [
                'id'  => 7,
                'name'  => 'Heath',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
