<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Insurance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class AxiosController extends Controller
{
    public function fetchCobs(Request $request)
    {
        $query = BusinessClass::query();

        if ($request->has('search') && $request->search) {
            $query->where('class_name', 'LIKE', '%' . $request->search . '%');
        }

        $items = $query->orderBy('id', 'asc')->paginate(15);

        return response()->json($items);
    }

    public function fetchClients(Request $request)
    {
        $query = User::role('client');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $items = $query->orderBy('id', 'asc')->paginate(15);

        return response()->json($items);
    }

    public function fetchClientsV2()
    {
        $query = User::select('id as value', DB::raw("
        CASE 
            WHEN CHAR_LENGTH(name) > 35 
            THEN CONCAT(LEFT(name, 35), '...') 
            ELSE name 
        END as label
    "))->role('client');
        $records = $query->orderBy('id', 'asc')->get();

        $data = [
            'clients' => $records,
        ];

        return response()->json($data);
    }

    public function fetchPolicyTypes()
    {
        $policy_types = [
            ['value' => 'new', 'label' => 'New'],
            ['value' => 'renewal', 'label' => 'Renewal'],
            ['value' => 'endorsement', 'label' => 'Endorsement'],
            ['value' => 'other', 'label' => 'Other'],
        ];

        $data = [
            'policy_types' => $policy_types,
        ];

        return response()->json($data);
    }

    public function fetchAgencies()
    {
        $agencies = Agency::select('id as value', 'name as label')->orderBy('id', 'asc')->get();
        $data = ['agencies' => $agencies];

        return response()->json($data);
    }

    public function fetchInsurers()
    {
        $insurers = Insurance::select('id as value', 'name as label')->orderBy('id', 'asc')->get();
        $data = ['insurers' => $insurers];

        return response()->json($data);
    }

    public function fetchCobsV2()
    {
        $cobs = BusinessClass::select('id as value', 'class_name as label')->orderBy('id', 'asc')->get();
        $data = ['cobs' => $cobs];

        return response()->json($data);
    }
}
