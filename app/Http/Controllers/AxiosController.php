<?php

namespace App\Http\Controllers;

use App\Models\BusinessClass;
use App\Models\User;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class AxiosController extends Controller
{
    public function fetchCobs(Request $request)
    {
        $query = BusinessClass::query();
        
        if ($request->has('search') && $request->search) {
            $query->where('class_name', 'LIKE', '%' . $request->search . '%');
        }
        
        $items = $query->orderBy('id','asc')->paginate(15);

        return response()->json($items);
    }

    public function fetchClients(Request $request)
    {
        $query = User::role('client');
        
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        
        $items = $query->orderBy('id','asc')->paginate(15);

        return response()->json($items);
    }
}
