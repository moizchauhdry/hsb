<?php

namespace App\Http\Controllers;

use App\Models\BusinessClass;
use Illuminate\Http\Request;

class AxiosController extends Controller
{
    public function fetchCobs()
    {
        $cobs = BusinessClass::select('id as value', 'class_name as label')->get()->toArray();
        $data = ['cobs' => $cobs];

        return response()->json($data);
    }
}
