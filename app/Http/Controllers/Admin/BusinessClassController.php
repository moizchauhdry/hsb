<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use App\Http\Controllers\Controller;
use App\Models\BusinessClassInsurance;

class BusinessClassController extends Controller
{
    public function index()
    {
        $businessClasses = BusinessClass::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($businessClass) => [
                'id' => $businessClass->id,
                'class_name' => $businessClass->class_name,
                'percentage' => $businessClass->percentage,
                'created_at' => $businessClass->created_at->format('d-m-Y h:i A'),
            ]);

      

        return Inertia::render('BusinessClass/Index', [
            'businessClasses' => $businessClasses,
            'businessClass' => NULL,
        ]);
    }

    public function create()
    {
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        
        $data = [
            'insurances' => $insurances,
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'class_name' => ['required', 'string', 'min:5', 'max:50'],
            'percentage' => ['required'],
            'insurance_id*' => ['required'],
        ]);

        $data = [
            'class_name' => $request->class_name,
            'percentage' => $request->percentage,
        ];

        $businessClasses = BusinessClass::create($data);

        if($request->insurance_id) 
        {
            $insuranceIDs = $request->insurance_id;
            foreach($insuranceIDs as $insuranceID)
            {
                $data = [
                    'insurance_id' => $insuranceID,
                    'business_class_id' => $businessClasses->id,
                ];

                BusinessClassInsurance::create($data);

            }
        }
    }

    public function edit($id)
    {        
        $businessClass = BusinessClass::find($id);
        $insurances = Insurance::select('id', 'name')->get()->toArray();

        $data = [
            'insurances' => $insurances,
            'businessClass' => $businessClass,
        ];


        return response()->json($data);
    }
}
