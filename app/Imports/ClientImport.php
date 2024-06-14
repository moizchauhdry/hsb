<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    public $errors = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (count($row) > 0 && !empty($row['name'])) {
            $validator = Validator::make($row, [
                'name' => 'required',
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                $this->errors[] = $validator->errors()->all();
                return null;
            }

            $client = User::updateOrCreate([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make('12345678'),
                'role_users_id' => 2,
            ]);

            $client->assignRole(2);

            return $client;
        }
    }
}
