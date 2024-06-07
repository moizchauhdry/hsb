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
                'name'  => 'Motor',
            ],
            [
                'name'  => 'Miscellaneous',
            ],
            [
                'name'  => 'Marine',
            ],
            [
                'name'  => 'Engineering',
            ],
            [
                'name'  => 'Life',
            ],
            [
                'name'  => 'Fire',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
