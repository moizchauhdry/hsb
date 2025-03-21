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
        $admin = User::updateOrCreate(['email' => 'moizchauhdry@gmail.com',], [
            'name' => 'Moiz Chauhdry',
            'email' => 'moizchauhdry@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');
    }
}
