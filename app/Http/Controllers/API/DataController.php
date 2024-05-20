<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\ClassOfBusiness;
use App\Models\Insurance;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function data()
    {
        $users = User::select('id', 'name')->get();
        $clients = User::select('id', 'name')->get()->toArray();
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $cobs = ClassOfBusiness::select('id', 'b_class_name')->get()->toArray();

        $data = [
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'cobs' => $cobs
        ];

        return response()->json($data);
    }
}
