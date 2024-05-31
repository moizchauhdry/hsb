<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name'  => 'Department 1',
            ],
            [
                'name'  => 'Department 2',
            ],
            [
                'name'  => 'Department 3',
            ]
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
