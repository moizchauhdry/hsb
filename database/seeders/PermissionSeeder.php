<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // Dashboard 10
            ['name' =>  'dashboard', 'guard_name' => 'web', 'order' => 10, 'level' =>  1],
            ['name' =>  'analytics', 'guard_name' => 'web', 'order' => 11, 'level' =>  2],

            // Role 20
            ['name' => 'role', 'guard_name' => 'web', 'order' => 20, 'level' => 1],
            ['name' => 'role_list', 'guard_name' => 'web', 'order' => 21, 'level' => 2],
            ['name' => 'role_create', 'guard_name' => 'web', 'order' => 22, 'level' => 2],
            ['name' => 'role_update', 'guard_name' => 'web', 'order' => 23, 'level' => 2],

            // User 30
            ['name' => 'user', 'guard_name' => 'web', 'order' => 30, 'level' => 1],
            ['name' => 'user_list', 'guard_name' => 'web', 'order' => 31, 'level' => 2],
            ['name' => 'user_create', 'guard_name' => 'web', 'order' => 32, 'level' => 2],
            ['name' => 'user_update', 'guard_name' => 'web', 'order' => 33, 'level' => 2],

            // Client 40
            ['name' => 'client', 'guard_name' => 'web', 'order' => 40, 'level' => 1],
            ['name' => 'client_list', 'guard_name' => 'web', 'order' => 41, 'level' => 2],
            ['name' => 'client_create', 'guard_name' => 'web', 'order' => 42, 'level' => 2],
            ['name' => 'client_update', 'guard_name' => 'web', 'order' => 43, 'level' => 2],

            // Policy 50
            ['name' => 'policy', 'guard_name' => 'web', 'order' => 50, 'level' => 1],
            ['name' => 'policy_list', 'guard_name' => 'web', 'order' => 51, 'level' => 2],
            ['name' => 'policy_create', 'guard_name' => 'web', 'order' => 52, 'level' => 2],
            ['name' => 'policy_update', 'guard_name' => 'web', 'order' => 53, 'level' => 2],
            ['name' => 'policy_detail', 'guard_name' => 'web', 'order' => 54, 'level' => 2],
            ['name' => 'policy_delete', 'guard_name' => 'web', 'order' => 55, 'level' => 2],

            ['name' => 'policy_claim', 'guard_name' => 'web', 'order' => 56, 'level' => 2],
            ['name' => 'policy_note', 'guard_name' => 'web', 'order' => 57, 'level' => 2],
            ['name' => 'policy_upload', 'guard_name' => 'web', 'order' => 58, 'level' => 2],

            // Insurer 60
            ['name' => 'insurer', 'guard_name' => 'web', 'order' => 60, 'level' => 1],
            ['name' => 'insurer_list', 'guard_name' => 'web', 'order' => 61, 'level' => 2],
            ['name' => 'insurer_create', 'guard_name' => 'web', 'order' => 62, 'level' => 2],
            ['name' => 'insurer_update', 'guard_name' => 'web', 'order' => 63, 'level' => 2],

            // Agency 70
            ['name' => 'agency', 'guard_name' => 'web', 'order' => 71, 'level' => 1],
            ['name' => 'agency_list', 'guard_name' => 'web', 'order' => 72, 'level' => 2],
            ['name' => 'agency_create', 'guard_name' => 'web', 'order' => 73, 'level' => 2],
            ['name' => 'agency_update', 'guard_name' => 'web', 'order' => 74, 'level' => 2],

            // COB 80
            ['name' => 'cob', 'guard_name' => 'web', 'order' => 80, 'level' => 1],
            ['name' => 'cob_list', 'guard_name' => 'web', 'order' => 81, 'level' => 2],
            ['name' => 'cob_create', 'guard_name' => 'web', 'order' => 82, 'level' => 2],
            ['name' => 'cob_update', 'guard_name' => 'web', 'order' => 83, 'level' => 2],

            // Report 90
            ['name' => 'report', 'guard_name' => 'web', 'order' => 90, 'level' => 1],
            ['name' => 'sales_report', 'guard_name' => 'web', 'order' => 91, 'level' => 2],
            ['name' => 'renewal_report', 'guard_name' => 'web', 'order' => 92, 'level' => 2],
            ['name' => 'outstanding_report', 'guard_name' => 'web', 'order' => 93, 'level' => 2],
            ['name' => 'commission_recovery_report', 'guard_name' => 'web', 'order' => 94, 'level' => 2],
            ['name' => 'commission_outstanding_recovery', 'guard_name' => 'web', 'order' => 95, 'level' => 2],

            // Setting 10
            ['name' => 'setting', 'guard_name' => 'web', 'order' => 100, 'level' => 1],
            ['name' => 'excel_import', 'guard_name' => 'web', 'order' => 101, 'level' => 2],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission['name'],
                'level' => $permission['level'],
                'order' => $permission['order'],
                'guard_name' => $permission['guard_name'],
            ]);
        }

        $role = Role::updateOrCreate(['name' => 'admin'], ['name' => 'admin']);
        Role::updateOrCreate(['name' => 'client'], ['name' => 'client']);

        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
    }
}
