<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Moiz Chauhdry',
            'email' => 'moizchauhdry@gmail.com',
            'password' => Hash::make('12345678'),
            'role_users_id' => 1,
        ];

        $user = User::updateOrCreate(['email' => 'moizchauhdry@gmail.com'], $data);

        $role = Role::updateOrCreate(['name' => 'Admin'], ['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
