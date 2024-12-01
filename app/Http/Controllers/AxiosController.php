<?php

namespace App\Http\Controllers;

use App\Models\BusinessClass;
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
}
