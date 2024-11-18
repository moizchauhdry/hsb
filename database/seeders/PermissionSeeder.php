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

            // Dashboard 01
            ['name' =>  'dashboard', 'guard_name' => 'web', 'order' => 1, 'level' =>  1],

            // Role 02
            ['name' => 'role', 'guard_name' => 'web', 'order' => 2, 'level' => 1],
            ['name' => 'role_list', 'guard_name' => 'web', 'order' => 2, 'level' => 2],
            ['name' => 'role_create', 'guard_name' => 'web', 'order' => 2, 'level' => 2],
            ['name' => 'role_update', 'guard_name' => 'web', 'order' => 2, 'level' => 2],

            // User 03
            ['name' => 'user', 'guard_name' => 'web', 'order' => 3, 'level' => 1],
            ['name' => 'user_list', 'guard_name' => 'web', 'order' => 3, 'level' => 2],
            ['name' => 'user_create', 'guard_name' => 'web', 'order' => 3, 'level' => 2],
            ['name' => 'user_update', 'guard_name' => 'web', 'order' => 3, 'level' => 2],

            // Policy 04
            ['name' => 'policy', 'guard_name' => 'web', 'order' => 4, 'level' => 1],
            ['name' => 'policy_list', 'guard_name' => 'web', 'order' => 4, 'level' => 2],
            ['name' => 'policy_create', 'guard_name' => 'web', 'order' => 4, 'level' => 2],
            ['name' => 'policy_update', 'guard_name' => 'web', 'order' => 4, 'level' => 2],

            // Client 05
            ['name' => 'client', 'guard_name' => 'web', 'order' => 5, 'level' => 1],
            ['name' => 'client_list', 'guard_name' => 'web', 'order' => 5, 'level' => 2],
            ['name' => 'client_create', 'guard_name' => 'web', 'order' => 5, 'level' => 2],
            ['name' => 'client_update', 'guard_name' => 'web', 'order' => 5, 'level' => 2],

            // Insurer 06
            ['name' => 'insurer', 'guard_name' => 'web', 'order' => 6, 'level' => 1],
            ['name' => 'insurer_list', 'guard_name' => 'web', 'order' => 6, 'level' => 2],
            ['name' => 'insurer_create', 'guard_name' => 'web', 'order' => 6, 'level' => 2],
            ['name' => 'insurer_update', 'guard_name' => 'web', 'order' => 6, 'level' => 2],

            // Agency 07
            ['name' => 'agency', 'guard_name' => 'web', 'order' => 7, 'level' => 1],
            ['name' => 'agency_list', 'guard_name' => 'web', 'order' => 7, 'level' => 2],
            ['name' => 'agency_create', 'guard_name' => 'web', 'order' => 7, 'level' => 2],
            ['name' => 'agency_update', 'guard_name' => 'web', 'order' => 7, 'level' => 2],

            // COB 08
            ['name' => 'cob', 'guard_name' => 'web', 'order' => 8, 'level' => 1],
            ['name' => 'cob_list', 'guard_name' => 'web', 'order' => 8, 'level' => 2],
            ['name' => 'cob_create', 'guard_name' => 'web', 'order' => 8, 'level' => 2],
            ['name' => 'cob_update', 'guard_name' => 'web', 'order' => 8, 'level' => 2],

            // Report 09
            ['name' => 'report', 'guard_name' => 'web', 'order' => 9, 'level' => 1],
            ['name' => 'sales_report', 'guard_name' => 'web', 'order' => 9, 'level' => 2],
            ['name' => 'renewal_report', 'guard_name' => 'web', 'order' => 9, 'level' => 2],
            ['name' => 'outstanding_report', 'guard_name' => 'web', 'order' => 9, 'level' => 2],
            ['name' => 'commission_recovery_report', 'guard_name' => 'web', 'order' => 9, 'level' => 2],
            ['name' => 'commission_outstanding_recovery', 'guard_name' => 'web', 'order' => 9, 'level' => 2],
        ];


        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission['name'],
                'level' => $permission['level'],
                'order' => $permission['order'],
                'guard_name' => $permission['guard_name'],
            ]);
        }
    }
}
