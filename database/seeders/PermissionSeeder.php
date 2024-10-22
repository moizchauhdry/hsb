<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // Basic
            'dashboard',

            // Role
            'role-list',
            'role-create',
            'role-update',

            // User
            'user-list',
            'user-create',
            'user-update',

            // Permission
            'policy-list',
            'policy-create',
            'policy-update',

            // // Report
            // ['name' => 'report', 'level' => 1],
            // ['name' => 'report_sale', 'level' => 2],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['name' => $permission]);
        }
    }
}
